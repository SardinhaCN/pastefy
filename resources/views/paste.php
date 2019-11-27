<?php tmpl("header", ["title"=>"Paste"]); ?>
<script>
hljs.initHighlightingOnLoad();
const embedLink = '<iframe width="100%" src="https://pastefy.ga/api/v1/embed/<?php echo ($id); ?>" onload="this.style.height = this.contentWindow.document.body.scrollHeight + \'px\';" frameborder="0" allowfullscreen></iframe>';
</script>
<div class="content">
    <?php if($needspassword):?>
        <div id="web_contents">
            <form action="/p/login/<?php echo ($id); ?>" method="POST">
                <a>This Paste is protected with a password!</a>
                <input class="titleinput" type="text" name="password" placeholder="Password">
                <input class="submitbutton" type="submit" name="sub" value="Send">
            </form>
        </div>
    <?php else: ?>
        <div class="pastecode" id="paste_code_container">
            <h2 id="paste_title" style="font-weight: normal"><?php echo ($pastetitle); ?></h2>
            <div id="copyRaw"><pre><code><?php echo ($code); ?></code></pre></div>
            <div id="pasteButtons">
                <a onClick='showSnackBar("Copied"); copyStringToClipboard(document.getElementById("copyRaw").textContent);'><i class="material-icons copybtn">content_copy</i> </a>
                <a class="link1" href="/<?php echo ($id); ?>/raw"> Raw</a>
                <a class="link1" href="/<?php echo ($id); ?>/raw" download="<?php echo ($pastetitle); ?>"> Download</a>
                <a onClick='showSnackBar("Copied embed code"); copyStringToClipboard(embedLink);' class="link1"> Embed</a>
                <?php if($mypaste):?>
                    <a class="link1" href="/delete:paste/<?php echo ($id); ?>"> Delete</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="pastecode" id="language_feature_container" style="display: none;"></div>
    <?php endif; ?>
</div>

<script>

var language = "text";

var markdowned = false;

function setLanguageFeatured(lang){
    $("#paste_title").append("<span class='language_featured'>"+lang+"</span>");
    language = lang;
}

function regreshFeaturedLanguage(){
    const container = $("#language_feature_container");
    container.show();

    if (language = "markdown" && !markdowned) {
        Cajax.post("/api/v1/language/markdown", {markdown: $("#copyRaw").text()}).then(function(response){
            const parsed = JSON.parse(response.responseText);
            container.html(parsed.out);
            hljs.initHighlighting.called = false;
            hljs.initHighlighting();
        }).send();
        markdowned = true;
    }
}

addEventListener('load', function() {
    if ($("code").getFitstElement().classList.contains("markdown")) {
        setLanguageFeatured("markdown");
    }


    $(".language_featured").click(function(){
        regreshFeaturedLanguage();
    });



    if (window.location.hash == "#preview")
        $(".language_featured").getFitstElement().click();
    else if (window.location.hash == "#md_preview") {
        setLanguageFeatured("markdown");
        regreshFeaturedLanguage();
    } else if (window.location.hash == "#only_md_preview") {
        setLanguageFeatured("markdown");
        regreshFeaturedLanguage();
        $("#paste_code_container").hide();
    }
});

</script>


<style>
    .language_featured {
        background: #00000022;
        border-radius: 4px;
        margin-left: 14px;
        padding: 0px 14px;
        cursor: pointer;
        user-select: none;
    }

    #language_feature_container {
        padding: 6px 15px;
    }

    #language_feature_container code {
        background: #32323296;
        margin-top: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    li {
        margin-left: 20px;
    }
</style>

<?php tmpl("footer"); ?>