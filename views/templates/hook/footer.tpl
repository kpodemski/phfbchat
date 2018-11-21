{if $phfbchat_init}
<div id="fb-root"></div>
{/if}
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = 'https://connect.facebook.net/{$phfbchat_locale}/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-customerchat"
page_id="{$phfbchat_page_id}"
{if $phfbchat_logged_in}
logged_in_greeting="{$phfbchat_logged_in}"
{/if}
{if $phfbchat_logged_out}
logged_out_greeting="{$phfbchat_logged_out}"
{/if}
{if $phfbchat_theme_color}
theme_color="{$phfbchat_theme_color}"
{/if}
{if $phfbchat_greeting_dialog_delay}
greeting_dialog_delay="{$phfbchat_greeting_dialog_delay}"
{/if}
></div>
