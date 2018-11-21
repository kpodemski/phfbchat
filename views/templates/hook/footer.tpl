{if $phfbchat_init}
<script>
(function (d) {ldelim}
    var js, id = 'facebook-jssdk';
    if (d.getElementById(id)) {ldelim}
        return;
    {rdelim}
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/{$phfbchat_locale}/sdk/xfbml.customerchat.js";
    d.getElementsByTagName('head')[0].appendChild(js);
{rdelim}(document));

window.fbAsyncInit = function () {ldelim}
    FB.init({ldelim}
       appId: {$phfbchat_app_id},
        autoLogAppEvents : true,
        xfbml : true,
        version : 'v3.1'
    {rdelim});
{rdelim};
</script>
{/if}
<div
    class="fb-customerchat"
    page_id="{$phfbchat_page_id}"
    ref="website"
    minimized="true"
></div>