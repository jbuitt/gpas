#!/usr/bin/perl -w
use Crypt::SmbHash;
use strict;
die "Usage: $0 password\n" if $#ARGV < 0;
my $password = $ARGV[0];
my($nt, $lm) = ntlmgen($password);
print $nt . ':' . $lm;
exit 0;
