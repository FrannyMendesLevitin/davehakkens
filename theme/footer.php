  <script>
    var homeURL = '<?php bloginfo( 'home' ); ?>';
    var templateURL = '<?php bloginfo( 'template_url' ); ?>';
  </script>

  <div id="overlay">
    <div id="loader">
      <img src="<?php bloginfo( 'template_url' ); ?>/img/loading.gif">
    </div>
  </div>

  <?php wp_footer(); ?>

<div id="note-topbar">
    <a href="https://www.patreon.com/davehakkens ">Support our projects on Patreon so we can keep developing 💪</a>
</div>

  <script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
      function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
      e=o.createElement(i);r=o.getElementsByTagName(i)[0];
      e.src='https://www.google-analytics.com/analytics.js';
      r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create','UA-64570160-1','auto');ga('send','pageview');
  </script>

</body>
</html>
