
## <span lang="en">What is it?</span> <span lang="ru">Что это?</span>
<span lang="en">A simple plug-in for Movable Type that will try to decode incoming Trackback pings for you into the character set of your blog.</span>

<span lang="ru">Плагин для Movable Type, который пытается раскодировать поступающие в систему пинги trackback при получении в кодировку вашего блога.</span>


## <span lang="en">Why is this needed</span> <span lang="ru">Зачем это</span>

<span lang="en">Because when Mena and Ben wrote the Trackback specification they forgot that there are people with different alphabets, and, more specifically, using other character sets than theirs. If you get a trackback, say, from a Korean blog, and your blog and the Korean blog do not use the same charset (UTF-8 for instance) it will look like giberrish on your page. This plugin tries to convert the incoming TrackBack ping into the encoding of your blog. Please note that it doesn't handle escaping of the incoming ping, validation of tags within it or encoding ampersands - you have to take care of these issues yourself if you care. **It's all about the charset.**</span>

<span lang="ru">Для того, чтобы как-то заткнуть дыру в спецификации trackback, которая никак не упоминает кодировки. Если вы получаете пинг trackback, например, от блога в кодировке windows-1251, а сами при этом содержите блог в кодировке utf-8, полученный пинг (случись ему содержать русский текст) будет выглядеть совершенно непристойно. Этот плагин пытается определить, в какой кодировке к вам пришел пинг, и раскодирует его, если эта кодировка отлична от применяемой Вами. Пожалуйста заметьте, что плагин не выполняет никаких дополнительных преобразований (как то кодирование амперсантов и пр.).</span>

## <span lang="en">What you need and how to use it</span> <span lang="ru">Что нужно для плагина и как им пользоваться</span>

<span lang="en">A recent version of MT (3.1+) and Perl 5.8+ (needed to handle decoding properly). To use it just, download the zipped plugin, unzip it and upload it into your plugins folder inside your Movable Type dir. That's it. You should see the plugin info on your blogs page.</span>

<span lang="ru">Свежая версия MT (3.1+) и свежая версия Perl (5.8+). Скачайте плагин, разархивируйте и положите в папку plugins в вашей директории Movable Type. Если плагин установлен, на экране с блогами MT появится его описание.</span>

## <span lang="en">How it works (detailed)</span> <span lang="ru">Как это работает (подробно)</span>

<span lang="en">First the plugin will see if the pinger sent the character set declaration along with his ping (in the Content-type request header). If he did (recent versions of MT send their publish charset, other recent Trackback implementations probably do as well - and if you will write one it should do it too), the plugin will assume that the ping itself is in this character set.  If the header was not provided, the plugin checks if the incoming ping is a valid UTF-8 byte sequence, and if it is - flags it as Unicode. If this fails, it will use the character set specified in the source of the plugin (the "fallback" charset). Please note that it might be a good idea to change it to the one suiting you best (i.e. if you expect, for instance, a special Easter-european encoding to be recieved often, use it's IANA name as FallbackCharset) </span>

<span lang="en">If the charset of the ping is different from the one you use on your blog (your PublishCharset), TrackDecode will recode it into your publish charset.</span>

<span lang="en">__Please note that depending of the text sent and the character set you use on your blog some characters can be lost at this stage__, i.e. if you use ISO-8991 and want to recieve something in EUC-KR it will most likely just get truncated. To avoid this, you should publish your blog in UTF-8.</span>


<span lang="ru">При получении пинга плагин проверяет, не отправил ли сайт-отправитель заголовок charset вместе с пингом (свежие версии MT такой заголовок отправляют, помещая в него Publish Charset блога-отправителя). Если заголовок присутствует, считается что полученный пинг содержит текст в кодировке, указанной этим заголовком. Если заголовок отсутствует, плагин проверяет содержимое пинга на соответствие порядку байтов UTF-8 строки. Если содержимое соответствует, то считается, что пинг в UTF-8. Если не соответствует, используется кодировка "по умолчанию" - на данный момент, Windows-1251. Эту настрйоку можно поменять в исходнике плагина.</span>

<span lang="ru">Если кодировка пинга не соответствует кодировке вашего блога (publish charset)  плагин перекодирует пинг в ваш publish charset явным образом перед тем, как пинг будет сохранен.</span>

<span lang="ru">__Имейте в виду что в зависимости от присланного текста и кодировки вашего блога эта операция может быть деструктивной__, то есть какие-то символы (не соответствующие вашему publish charset или отсутвующие в  нем) скорее всего будут просто отброшены при конвертации. Чтобы этого избежать, публикуйте ваш MT-сайт в кодировке UTF-8.</span>

<span lang="en">As this trackback is primarly targeted at Russian users, the default fallback charset is windows-1251. However, you can easily change it to the one you need in the source of the plugin (when I know how to implement this as a comfy menu inside your MT blog screen I will let you know, ok?).</span>

<span lang="en">When the  ping gets decoded by TrackDecode a message is left in the system logs. By looking at remote IP (the IP of the sender) and time you can find the related ping in your trackbacks screen. If the ping did not have to be recoded it will be left intact and no message will ever be generated.</span>

<span lang="ru">Когда плагин перекодирует пинг, в activity log остается об этом запись, включая указание, какую кодировку TrackDecode использовал как исходную.</span>

## <span lang="en">How to make it better</span> <span lang="ru">Как сделать это лучше</span>
<span lang="en">If you can implement something that would be useful for the plugin (for example, an MT-hooked interface in the TrackBacks section (Plugin Actions -> Decode this ping) or a better charset detection pattern, it would be nice.This is the first thing I ever wrote in my life in Perl, so don't be too stringent about the code too.</span>

<span lang="ru">Если вы можете реализовать что-то что было бы полезно иметь в плагине (например кнопку Decode selected pings в списке plug-in actions окна trackbacks)  или более надежный способ определения кодировки (например отличить KOI от WIN) - было бы неплохо. Учитывая что этот плагин - первое, что я вообще написал в жизни на Perl, качество кода (а возможно и его эффективность) оставляют желать.</span>


## <span lang="en">Whom to thank.</span> <span lang="ru">Кому говорить спасибо, если соберетесь</span>
<span lang="en">Incidentially, Jacques Distler raised the issue of trackback decoding on his blog about 2 days before I started to write this plugin, and the things he wrote in his entry were very useful for me when implementing this plugin. Further, [Tatsuhiko Miyagawa](http://bulknews.net) was of big help - the plugin is built based on his code that moderates comments subnmitted in ASCII.</span>

<span lang="ru">Так случилось, что Жак Дистлер поднял вопрос перекодирования пингов за несколько дней до того, как я начал писать плагин. К тому же узнал я об этом уже посреди работы, и комментарии и сама запись Дистлера сильно помогла. Отдельное спасибо [Tatsuhiko Miyagawa](http://bulknews.net) - его плагин BanASCII послужил основой для TrackDecode.</span>