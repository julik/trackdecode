package MT::Plugin::TrackDecode;
# TrackDecode.pl
# Tries to detect and decode trackbacks sent as CP-1251 into UTF-8 and vice-versa.
# If the pinger sends charset headers with his POST request this charset weill be used instead.
#
# Based on BanASCII plugin by Tatsuhiko Miyagawa <miyagawa at bulknews.net>
# Special thanks for advice and support to Jacques Distler <distler at golem.ph.utexas.edu>
#
# Drop this in your plugins folder.
#
# It requires Perl 5.8 or over.
#
# Author:  Julian Tarkhanov <me at julik.nl>
# License: same as Perl
#

use strict;
our $VERSION = "0.26";

use MT;
use MT::Plugin;
use Encode;

my $plugin = MT::Plugin->new({
    name => "TrackDecode v$VERSION",
    description => "Assists in recieving trackbacks from blogs using other character sets",
});

our $fallbackCharset = 'windows-1251';

# this is for future release - we should allow blog-based selection of fallback charset
# MT::ConfigMgr->instance->define ("TrackDecodeFallbackCharset", Default=>'windows-1251');


 #here we hook ourselves into the callback
MT->add_plugin($plugin);
MT->add_callback('TBPingFilter', 2, $plugin, \&_process_ping);

sub _process_ping {
    my($eh, $app, $ping) = @_;
    
    my $charsetTo = MT::ConfigMgr->instance->PublishCharset;
    my $charsetFrom = MT::ConfigMgr->instance->TrackDecodeFallbackCharset;
	
	
 	if ( !length($charsetFrom) ) {
 		$charsetFrom = $fallbackCharset; #default fallback charset
 	}
 	
   	require Encode;
   	     	
  	my $excerpt = $ping->excerpt;
  	my $title = $ping->title;
  	my $blog_name = $ping->blog_name;

    my $textSample1 = $excerpt . $title . $blog_name;
    my $textSample2 = $title . $blog_name . $excerpt;
    my $textSample3 = $blog_name . $excerpt . $title;
    
	# undo byte packing done by MT so that we get strings back. Idiots.
  	_unpack ($excerpt, $title, $blog_name);
  	  	  
    # use the W3 UTF-8 detection pattern - AND IS FAILS OFTEN!
    if ($ENV{'CONTENT_TYPE'} =~ /[Cc]harset=([a-zA-Z0-9-]+)/ && length($1)) {
#    	$app->log("TD: Ping was sent with header of " . $1);
	    $charsetFrom = $1;
	} elsif (_matches_utf8($excerpt) && _matches_utf8($title) && _matches_utf8($blog_name)) {
#    	$app->log("TD: Ping matched as UTF-8 by regexp");
        $charsetFrom = 'utf-8';	
    } else {
#    	$app->log("TD: Ping matched as non-UTF-8, fallback charset will be used");
    }
    
#	$app->log("TD: Dest charset is " . $charsetTo);
    
    if ($charsetFrom ne $charsetTo) {
   		$app->log("TD: Transcoding ping from " . $charsetFrom . " into " . $charsetTo . ". Thank you.");		
		$ping = _decode_ping($ping, $charsetFrom, $charsetTo);
    }
    
    return 1;
}

## unpack string from bytes after MT gives it to us
sub _unpack {	for (@_) {		next if !defined $_;		$_ = unpack 'C0A*', $_;	}
}

## decode ping
sub _decode_ping {
	my ($ping, $charsetFrom, $charsetTo) = @_;

    require Encode;
    
	my $excerpt = $ping->excerpt;
	my $title = $ping->title;
	my $blog_name = $ping->blog_name;

	Encode::from_to($excerpt, $charsetFrom, $charsetTo);
	Encode::from_to($title, $charsetFrom, $charsetTo);
	Encode::from_to($blog_name, $charsetFrom, $charsetTo);		
		
	$ping->excerpt($excerpt);
  	$ping->title($title);
  	$ping->blog_name($blog_name);
  	
  	return $ping;
}

## check if a string is in UTF-8 byte order using W3.org pattern
sub _matches_utf8 {
    $_ = shift;
    m/^(
       [\x09\x0A\x0D\x20-\x7E]            # ASCII
     | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
     |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
     | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
     |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
     |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
     | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
     |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$/x;
}

1;