<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'HC Interiors';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(!preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){ ?>[DEV] <?php } ?>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base3.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    
    
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">


    
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/1.10.16/features/searchHighlight/dataTables.searchHighlight.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/css/jquery.tagit.css">
    
	
    <?php if($this->request->params['action'] == 'schedule' || $this->request->params['action'] == 'pendingschedule' || $this->request->params['action'] == 'completedschedule'){ ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	
	<link href="/css/daterangepicker.min.css" type="text/css" rel="stylesheet" />
	<script src="/js/jquery.daterangepicker.min.js"></script>
	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css" type="text/css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js"></script>
	
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script src="/js/jquery.highlight.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.13/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/pdfmake.min.js.map"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/pdfmake.min.js"></script>
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	
	<?php }else{ ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="/js/jquery.highlight.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.13/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/pdfmake.min.js.map"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.34/pdfmake.min.js"></script>
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	<?php } ?>
    
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
	<script src="/js/jquery.ba-throttle-debounce.min.js"></script>
  	<script src="/js/tag-it.min.js"></script>
	<script src="/js/additional.js"></script>
	
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>
    <script>
    $(function(){
        $('#imagelibrarycontents img').lazy({appendScroll:'#imagelibrarycontents'});
        /*PPSASCRUM-78: start*/
        $('#sessionTimeoutModal').css('display', 'none');
        $('#session-modal-ok-btn').click(function () {
            $('#sessionTimeoutModal').css('display', 'none');
            localStorage.setItem(window.name+"_redirectUrl_"+getHostName(), localStorage.getItem(window.name+"_lastActivityUrl_"+getHostName()));
            window.location='/users/logout';
            localStorage.removeItem('logoutTime_'+getHostName());
            hasRedirected = true;
        })
        if (window.location.href.includes('users/login')) {
            localStorage.removeItem('logoutTime_'+getHostName());
        }
        /*PPSASCRUM-78: end*/
        
        
    });
     
    
   /*PPSASCRUM-269 start */
    function moveToNextFocusableElement(currentElement) {
        var focusableElements = $('input:visible, textarea:visible, select:visible, radio:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]:not([tabindex="-1"])');
        var currentIndex = focusableElements.index(currentElement);

        if (currentIndex >= 0 && currentIndex < focusableElements.length - 1) {
            var nextElement = focusableElements.eq(currentIndex + 1);
            nextElement.focus();
        }
    }
    
    /*PPSASCRUM-269 end */
    </script>
    
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">

    <style>
    
    @font-face {
        font-family: 'metropolislight';
        src: url('/font/metropolis-light-webfont.woff2') format('woff2'),
             url('/font/metropolis-light-webfont.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    
    }
    
    
    
    
    @font-face {
        font-family: 'metropolislight_italic';
        src: url('/font/metropolis-lightitalic-webfont.woff2') format('woff2'),
             url('/font/metropolis-lightitalic-webfont.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    
    }
    
    
    
    
    @font-face {
        font-family: 'metropolisregular';
        src: url('/font/metropolis-regular-webfont.woff2') format('woff2'),
             url('/font/metropolis-regular-webfont.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    
    }
    
    
    
    
    @font-face {
        font-family: 'metropolisregular_italic';
        src: url('/font/metropolis-regularitalic-webfont.woff2') format('woff2'),
             url('/font/metropolis-regularitalic-webfont.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    
    }
    
    table.dataTable thead th{ font-size:11px !important; }
    
    table#quoteitems tbody td,
    table.dataTable tbody td,
    div.newquote.step2.form{ font-family:'Helvetica', sans-serif !important; }
    
    <?php
    if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
        echo "body > header{ background-color:#FFF !important; font-family:'metropolislight'; padding:30px 80px 15px 80px !important; }
        header nav#topmenu ul > li{ background:#004A87 !important; }
        
        #welcomebar{ background:#333333 !important; color:#FFF; font-family:'metropolislight'; }
        #welcomebar a{ color:#f8f8f8; }
        
        button{ background:#004A87; }
        
        .dataTable thead tr{ background:#004A87 !important; }
        .dataTable thead tr th{ color:#FFF !important; }
        
        .dataTable tfoot tr{ background:#004A87 !important; }
        .dataTable tfoot tr th{ color:#FFF !important; }
        
        .dataTable tbody tr:nth-of-type(even){
        	background:#fff;
        }
        .dataTable tbody tr:nth-of-type(odd){
        	background:#f8f8f8;
        }
        .dataTable tbody tr:hover{
        	background:#e8e8e8;
        }
        
        h1.pageheading{ font-size:x-large; color:#004A87; text-align:center; }
        
        header nav#topmenu ul > li > ul > li:hover > a {
            background: #71BD1D;
            color: #FFF;
        }
        body:before{ content:''; width:100%; height:15px; background:#004A87; position:fixed; top:0; left:0; z-index:9999; }
        ";
    }else{
        echo "body > header{ background-color:#74BC1F !important; }
        header nav#topmenu ul > li{ background:#9CE447 !important; }
        button{ background:#9CE447 !important; }
        
        #welcomebar{ background:#333333 !important; color:#FFF; font-family:'metropolislight'; }
        #welcomebar a{ color:#f8f8f8; }
        
        .dataTable thead tr{ background:#74BC1F !important; }
        .dataTable thead tr th{ color:#FFF !important; }
        
        .dataTable tfoot tr{ background:#74BC1F !important; }
        .dataTable tfoot tr th{ color:#FFF !important; }
        
        .dataTable tbody tr:nth-of-type(even){
        	background:#fff;
        }
        .dataTable tbody tr:nth-of-type(odd){
        	background:#f8f8f8;
        }
        .dataTable tbody tr:hover{
        	background:#e8e8e8;
        }
        
        h1.pageheading{ font-size:x-large; color:#9CE447; text-align:center; }
        
        header nav#topmenu ul > li > ul > li:hover > a {
            background: #FF0000;
            color: #FFF;
        }
        
        table#quoteitems thead{ background: #74BC1F !important; }
        
        body:before{ content:''; width:100%; height:15px; background:#74BC1F; position:fixed; top:0; left:0; z-index:9999; }
        ";
        
    }
    ?>
    
    @media only print{
        body:before{ display:none !important; }
    }

    /*PPSASCRUM-78: start*/
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 400px;
      text-align: center;
    }
    /*PPSASCRUM-78: end*/
    </style>
	
</head>
<body>
    <header>
        <div class="header-title">
            <a href="/"><img src="/img/<?php
    if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
        echo "HCI-Logo.png\" width=\"250\"";
    }else{
        echo "HCI-Logo-dev.png\" width=\"250\"";
    }
    ?>" alt="HC Interiors" title="HC Interiors" /></a>
        </div>
        
        <nav id="topmenu">
        <?php echo $menuList; ?>
        </nav>
        <div class="clear"></div>
    </header>
    
    <div id="welcomebar">
    <?php if ($this->request->session()->read('Auth.User')){ ?>
        
        	<!--PPSASCRUM-78: start-->
        	<!--	Welcome, <strong><//?php echo $this->request->session()->read('Auth.User.first_name'); ?></strong> <a href="/users/logout/">Log Out</a>-->
        	Welcome, <strong><?php echo $this->request->session()->read('Auth.User.first_name'); ?></strong> <a href="/users/logout/" onclick="releaseResources()">Log Out</a>
            <!--PPSASCRUM-78: end-->
            <?php }else{ ?>
            Welcome, <strong>Guest</strong> <a href="/users/login/">Log In</a>
            <?php } ?>
    </div>
    
    <div id="container">
        <div id="content">
            <?= $this->Flash->render() ?>

            <div class="row">
                <?= $this->fetch('content') ?>
            </div>
        </div>
        <footer>
        </footer>
    </div>
    <!--PPSASCRUM-78: start-->
    <div id="sessionTimeoutModal" class="modal">
        <div class="modal-content">
            <h2>Session Timeout</h2>
            <p>Your session is expired. Please click OK to return to the login screen</p>
            <button id="session-modal-ok-btn">OK</button>
        </div>
    </div>
    <script>
        if (!window.name || window.name == '') {
            window.name = Math.random().toString(36).slice(2);
        }
        if ((window.location.pathname == '/' && performance.getEntriesByType('navigation').filter(entry => entry.type == 'reload').length > 0) || window.location.pathname == '/null') {
            window.location = localStorage.getItem(window.name+"_redirectUrl_"+getHostName()) ? localStorage.getItem(window.name+"_redirectUrl_"+getHostName()) : '/';
        }
        if (<?php echo $this->request->session()->read('Auth.User') ? 'true' : 'false' ?>) {
            if (localStorage.getItem('logoutTime_'+getHostName()) == null) {
                window.location = localStorage.getItem(window.name+"_lastActivityUrl_"+getHostName()) ? localStorage.getItem(window.name+"_lastActivityUrl_"+getHostName()) : '/';
            }
            var restrictedUrls = ['/orders/schedule/', '/orders/pendingschedule/', '/orders/completedschedule/'];
            var userLoginTime = Math.ceil(new Date().getTime() / 1000);
            var userLogoutTime = userLoginTime + parseInt(<?php echo $sessionDuration ?>) * 60;
            localStorage.setItem('logoutTime_'+getHostName(), userLogoutTime);
            if (!window.location.pathname.includes('/users/logout')) {
                localStorage.setItem(window.name+"_lastActivityUrl_"+getHostName(), window.location.pathname);
            }
            var hasRedirected = false;
            localStorage.setItem('isRestrcitedUrl_'+getHostName(), 'false');
            if (restrictedUrls.filter(url => window.location.href.includes(url)).length > 0) {
                setInterval(function () {
                    if (restrictedUrls.filter(url => window.location.href.includes(url)).length == 0) {
                        localStorage.setItem('isRestrcitedUrl_'+getHostName(), 'false');
                    } else {
                        localStorage.setItem('isRestrcitedUrl_'+getHostName(), 'true');
                    }
                }, 50);
            }
            setInterval(function() {
                let logoutTime = parseInt(localStorage.getItem('logoutTime_'+getHostName()));
                if (restrictedUrls.filter(url => window.location.href.includes(url)).length == 0) {
                    if (Math.ceil(new Date().getTime() / 1000) > logoutTime && !hasRedirected && localStorage.getItem('isRestrcitedUrl_'+getHostName()) != 'true') {
                        console.log('Session expired!');
                        $('#sessionTimeoutModal').css('display', 'block');
                        localStorage.setItem(window.name+"_redirectUrl_"+getHostName(), localStorage.getItem(window.name+"_lastActivityUrl_"+getHostName()));
                        hasRedirected = true;
                        return;
                    }
                }
            }, 50);
        }

        function getHostName() {
            return location.host.split('.')[0];
        }

        function releaseResources() {
            localStorage.removeItem(window.name+'_lastActivityUrl_'+getHostName());
            localStorage.removeItem('logoutTime_'+getHostName());
            localStorage.removeItem(window.name+"_redirectUrl_"+getHostName());
            localStorage.removeItem('isRestrcitedUrl_'+getHostName());
        }
        
      
        
    </script>
    <!--PPSASCRUM-78: end-->
</body>
</html>
