TrackDecode
Trackback decoder for Movable Type

Release 0.26
March 20, 2005


From Julian "Julik" Tarkhanov
http://live.julik.nl/
Leave the copyrights to your granny.

===========================================================================

DESCRIPTION

This plug-in will decode all incomign Trackback pings into the PublishCharset of your blog (the one you set in mt.cfg). It will
try to guess the encoding of the incoming ping, and if it is different from yoour PublishCharset the plugin will decode it. This is
useful if you use your blog for publishing non-latin content (russian, eastern-european, hebrew, whatever) or if you recieve
this kind of content in pings from other blogs. For instance, if you publish your blog in UTF-8, and you recieve a ping from
a Hebrew user in Windows-155 encoding the ping will be decoded into UTF-8, and will be readable to you and your other Hebrew-speaking
readers.

When TrackDecode touches a ping there will be a message about this in your MT system log. Please note that auto-guessing does
not always work, so you might end up with screwed pings (for instance, if the sender is publishing in Win-1251 but did not set
his MTPublishCharset properly. But then he is probably an idiot and gets what he deserves). You can fight this in two ways:

a) There is a variable embedded in the source of the plugin called 

	our $fallbackCharset = 'windows-1251';

   This variable controls what encoding shall be assumed when the encoding of the recieved ping cannot be guessed.
   By default it is set to 'windows-1251', a default for Russian users, however, you can change it, for instance,
   to "windows-1255" if you are running a blog in Hebrew etc.

b) If a ping ended up scrambled in your installl - no big deal, go to the sender site and copy the excerpt into your pings
   table. This is the only solution for now.

===========================================================================



SYSTEM REQUIREMENTS



MovableType 3.1+
Perl 5.8+


===========================================================================



INSTALLATION



Drop the plugin into your plug-ins folder. Trackbacks will be decoded automatically
whenever an incoming ping is recieved.

Refer to the Movable Type documentation for more information regarding
plugins.


===========================================================================

LICENSE

You may freely use, copy, modify, merge, publish, distribute this Software
as long as you give a credit and a link to it's author as following:

	(c) Julik Tarkhanov, 2004 (http://julik.nl)


THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.

===========================================================================

SUPPORT

No support is offered for this plugin. However, if you have bugs to report or imporvements to include, send them my way.

===========================================================================

CHANGELOG

.026 - Initial release
