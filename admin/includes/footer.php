    </div>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
<script>
(function(){
  var ov = document.getElementById('adminSideOverlay');
  if (ov) ov.addEventListener('click', function(){
    document.body.classList.remove('side-open');
    ov.classList.remove('open');
  });
})();
</script>
</body>
</html>