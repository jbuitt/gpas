#!/usr/bin/perl -w

use strict;
use Net::LDAP;
use List::Util 'max';
use Data::Dumper;

my $ldap = Net::LDAP->new( '10.0.2.8' );

# bind to a directory with dn and password
my $mesg = $ldap->bind( 'cn=Directory Manager', password => 'f6h0myjJmBCW6JaElqRnRx5' );

$mesg = $ldap->search(	# perform a search
	base   => "ou=People,dc=punchkick,dc=com",
	filter => "uid=*"
);
if ($mesg->code != 0) {
	die $mesg->error;
}

my @uids = ();
my @gids = ();

foreach my $entry ($mesg->entries) {
	my $uidNumber = $entry->get_value('uidNumber');
	my $gidNumber = $entry->get_value('gidNumber');
	push(@uids, $uidNumber);
	push(@gids, $gidNumber);
}

my $next_uid = max(@uids) + 1;
my $next_gid = max(@gids);

print "$next_uid:$next_gid\n";

exit 0;
