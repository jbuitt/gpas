#!/usr/bin/perl -w

use strict;
use Net::LDAP;
use Crypt::SmbHash;
use Data::Dumper;

die "Usage: $0 /path/to/csv/file\n" if $#ARGV < 0;

my $ldap_host       = '10.0.2.8';
my $ldap_admin_dn   = 'cn=Directory Manager';
my $ldap_admin_pass = 'f6h0myjJmBCW6JaElqRnRx5';
my $ldap_base_dn    = 'ou=People,dc=punchkick,dc=com';

my @USERS = ();

open(INFILE, "$ARGV[0]") or die $!;
while(<INFILE>) {
	s/\r//g;
	s/\n//g;
	my @line = split(/,/);
	my %tmphash = ();
	$tmphash{'objectclass'}		= ['top', 'person', 'organizationalPerson', 'inetOrgPerson', 'posixAccount', 'sambaSamAccount'],
	$tmphash{'givenName'}		= $line[0];
	$tmphash{'sn'}			= $line[1];
	$tmphash{'uid'}			= $line[2];
	$tmphash{'mail'}		= $line[3];
	$tmphash{'cn'}			= $line[0] . ' ' . $line[1];
	$tmphash{'userPassword'}	= $line[2] . '1';
	$tmphash{'uidNumber'}		= $line[4];
	$tmphash{'gidNumber'}		= $line[5];
	$tmphash{'homeDirectory'}	= '/home/' . $line[2];
	$tmphash{'loginShell'}		= '/bin/bash';
	$tmphash{'gecos'}		= $line[0] . ' ' . $line[1];
	my $sambaSID = ($line[4]*2)+1000;
	$sambaSID = 'S-1-0-0-' . $sambaSID;
	$tmphash{'sambaSID'}            = $sambaSID;
	my ($sambaLMPassword,$sambaNTPassword) = ntlmgen($line[2] . '1');
	#$tmphash{'sambaLMPassword'}     = $sambaLMPassword;
	$tmphash{'sambaNTPassword'}     = $sambaNTPassword;
	push(@USERS, \%tmphash);
}
close(INFILE);

#print Dumper(\@USERS);
#exit;

my $ldap = Net::LDAP->new($ldap_host) or die "$@";

# bind to a directory with dn and password
my $mesg = $ldap->bind($ldap_admin_dn, password => $ldap_admin_pass);

# Add users
for(my $i=0; $i<@USERS; $i++) {
	print "Adding " . $USERS[$i]->{'cn'} . "..  ";
	my $dn = 'uid=' . $USERS[$i]->{'uid'} . ',' . $ldap_base_dn;
	#print "**DEBUG** \$dn = $dn\n";
	my $result = $ldap->add( $dn,
		attrs => [
			'objectclass' => $USERS[$i]->{'objectclass'},
			'cn' => $USERS[$i]->{'cn'},
			'givenName' => $USERS[$i]->{'givenName'},
			'sn' => $USERS[$i]->{'sn'},
			'mail' => $USERS[$i]->{'mail'},
			'uid' => $USERS[$i]->{'uid'},
			'userPassword' => $USERS[$i]->{'userPassword'},
			'uidNumber' => $USERS[$i]->{'uidNumber'},
			'gidNumber' => $USERS[$i]->{'gidNumber'},
			'homeDirectory' => $USERS[$i]->{'homeDirectory'},
			'loginShell' => $USERS[$i]->{'loginShell'},
			'gecos' => $USERS[$i]->{'gecos'},
			'sambaSID' => $USERS[$i]->{'sambaSID'},
			'sambaLMPassword' => $USERS[$i]->{'sambaLMPassword'},
			'sambaNTPassword' => $USERS[$i]->{'sambaNTPassword'},
			'sambaPwdLastSet' => time()
		]
	);
	$result->code && warn "failed to add entry: ", $result->error;
	print "Done.\n";
}

$mesg = $ldap->unbind;  # take down session

exit 0;

