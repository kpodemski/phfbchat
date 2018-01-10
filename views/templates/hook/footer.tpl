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
    js.src = "//connect.facebook.net/{$phfbchat_locale}/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
{rdelim}(document));

window.fbAsyncInit = function () {ldelim}
    FB.init({
        appId: {$phfbchat_app_id},
        xfbml            : true
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