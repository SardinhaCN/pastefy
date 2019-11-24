@template(("header", ["title"=>"Paste"]))!
<script>
hljs.initHighlightingOnLoad();
const embedLink = '<iframe width="100%" src="/api/v1/embed/{{$id}}" onload="this.style.height = this.contentWindow.document.body.scrollHeight + \'px\';" frameborder="0" allowfullscreen></iframe>';
</script>
<div class="content">
    @if(($needspassword))#
        <div id="web_contents">
            <form action="/p/login/{{$id}}" method="POST">
                <a>This Paste is protected with a password!</a>
                <input class="titleinput" type="text" name="password" placeholder="Password">
                <input class="submitbutton" type="submit" name="sub" value="Send">
            </form>
        </div>
    @else
        <div id="pastecode">
            <h2 style="font-weight: normal">{{$pastetitle}}</h2>
            <div id="copyRaw"><pre><code>{{$code}}</code></pre></div>
            <div id="pasteButtons">
                <a onClick='showSnackBar("Copied"); copyStringToClipboard(document.getElementById("copyRaw").textContent);'><i class="material-icons copybtn">content_copy</i> </a>
                <a class="link1" href="/{{$id}}/raw"> Raw</a>
                <a class="link1" href="/{{$id}}/raw" download="{{$pastetitle}}"> Download</a>
                <a onClick='showSnackBar("Copied embed code"); copyStringToClipboard(embedLink);' class="link1"> Embed</a>
                @if(($mypaste))#
                    <a class="link1" href="/delete:paste/{{$id}}"> Delete</a>
                @endif
            </div>
        </div>
    @endif
</div>

@template(("footer"))!