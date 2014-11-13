<div class="container" style="margin-top: 100px;">

    <form action="helper" role="form" method="get" style="display:block;width:680px; margin:0 auto;">
        <div class="form-group">
            <input name="url" type="text" class="form-control  ipt_text" value="<?php echo s_input('url'); ?>" placeholder="请输入要分析的URL地址，eg：http://www.baidu.com/test.php">&nbsp;&nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary" >查看</button>
        </div>
    </form>

    
<?php if($flag): ?> 
    <h2>站点“<?php echo $url_info['host']; ?>”请求信息：</h2><div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"小伙伴们，我刚刚在anyweb（http://anyweb.heroku.com）上测试<?php echo $url_info['host']; ?>的http请求，各项指标灰常不错，你也来试试吧！","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"32"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"32"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
    <div class="row">
        <div class="col-md-6">
            <ul>
                <li>ip地址: <?php echo $ip.'('.$ip_info['country'].' '.$ip_info['area'].')'; ?></li>
                <li>状态：<?php echo $head_info[0] ?> </li>
                <!-- <li>服务器：<?php echo $head_info['Server'] ?></li>
                <li>文件类型： <?php echo $head_info['Content-Type'] ?></li>
                <li>响应时间： <?php echo $head_info['Content-Length'] ?></li>
                <li>时间： <?php echo $head_info['Date'] ?></li>
                <li>文件类型： <?php echo $head_info['Content-Type'] ?></li>
                <li>文件类型： <?php echo $head_info['Content-Type'] ?></li> -->
                <li>HTML: <br><?php //echo $content['body'] ?></li>
            </ul>
            <?php if(!empty($content['header'])): ?>
            <pre>
<?php echo $content['header']; ?>
            </pre>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <iframe src="<?php echo $url_info['url']; ?>" frameborder="1" width="600" height="400"></iframe>
        </div>
    </div>
    
    <div class="row">
        <h3>Alexa Rank</h3>
        <div class="col-md-8">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#12m" role="tab" data-toggle="tab">一年</a></li>
              <li role="presentation"><a href="#6m" role="tab" data-toggle="tab">半年</a></li>
              <li role="presentation"><a href="#3m" role="tab" data-toggle="tab">三个月</a></li>
              <li role="presentation"><a href="#1m" role="tab" data-toggle="tab">一个月</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="12m">
                <img src="http://traffic.alexa.com/graph?w=700&h=280&r=12m&y=t&u=<?php echo $url_info['host']; ?>" alt="" width="700" height="280">
              </div>
              <div role="tabpanel" class="tab-pane" id="6m">
                <img src="http://traffic.alexa.com/graph?w=700&h=280&r=6m&y=t&u=<?php echo $url_info['host']; ?>" alt="" width="700" height="280">
              </div>
              <div role="tabpanel" class="tab-pane" id="3m">
                <img src="http://traffic.alexa.com/graph?w=700&h=280&r=3m&y=t&u=<?php echo $url_info['host']; ?>" alt="" width="700" height="280">
              </div>
              <div role="tabpanel" class="tab-pane" id="1m">
                <img src="http://traffic.alexa.com/graph?w=700&h=280&r=1m&y=t&u=<?php echo $url_info['host']; ?>" alt="" width="700" height="280">
              </div>
            </div>
        </div>
    </div>

<?php else: ?>
<?php echo $message; ?>
<?php endif; ?>
    
    

</div> 