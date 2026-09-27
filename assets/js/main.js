(function(){
  'use strict';

  /* ---------- Dynamic body padding for fixed header ---------- */
  var topWrap = document.querySelector('.site-top');
  function adjustPadding(){
    if(!topWrap) return;
    var h = topWrap.offsetHeight;
    document.body.style.paddingTop = h + 'px';
  }
  window.addEventListener('load', adjustPadding);
  window.addEventListener('resize', adjustPadding);
  setTimeout(adjustPadding, 80);
  adjustPadding();

  /* ---------- Prevent horizontal scroll from any element ---------- */
  function killOverflow(){
    var docW = document.documentElement.clientWidth;
    document.querySelectorAll('body *').forEach(function(el){
      if (el.offsetWidth > docW + 2) {
        var cs = getComputedStyle(el);
        if (cs.position !== 'fixed' && cs.overflowX === 'visible') {
          el.style.maxWidth = '100%';
        }
      }
    });
  }
  window.addEventListener('load', killOverflow);
  setTimeout(killOverflow, 400);

  /* ---------- Sticky shadow ---------- */
  var header = document.querySelector('.site-top');
  function onScroll(){
    if(header){
      if(window.scrollY > 8) header.classList.add('scrolled');
      else header.classList.remove('scrolled');
    }
    var t = document.getElementById('toTop');
    if(t){
      if(window.scrollY > 500) t.classList.add('show');
      else t.classList.remove('show');
    }
  }
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll('.reveal');
  if('IntersectionObserver' in window){
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }
      });
    }, {threshold:0.08, rootMargin:'0px 0px -40px 0px'});
    revealEls.forEach(function(el){ io.observe(el); });
  } else { revealEls.forEach(function(el){ el.classList.add('in'); }); }

  /* ---------- Mobile Drawer ---------- */
  var drawer = document.getElementById('drawer');
  var drawerOverlay = document.getElementById('drawerOverlay');
  function openDrawer(){
    if(!drawer) return;
    drawer.classList.add('open');
    if(drawerOverlay) drawerOverlay.classList.add('open');
    document.body.classList.add('modal-open');
  }
  function closeDrawer(){
    if(!drawer) return;
    drawer.classList.remove('open');
    if(drawerOverlay) drawerOverlay.classList.remove('open');
    document.body.classList.remove('modal-open');
  }
  window.openDrawer = openDrawer;
  window.closeDrawer = closeDrawer;
  if(drawerOverlay) drawerOverlay.addEventListener('click', closeDrawer);

  /* ---------- Auth Modal ---------- */
  var authModal = document.getElementById('authModal');
  function openAuthModal(tab){
    if(!authModal) return;
    authModal.classList.add('open');
    document.body.classList.add('modal-open');
    if(tab) switchAuthTab(tab);
    setTimeout(function(){
      var inp = authModal.querySelector('.tab-pane.active input');
      if(inp) inp.focus();
    }, 120);
  }
  function closeAuthModal(){
    if(!authModal) return;
    authModal.classList.remove('open');
    document.body.classList.remove('modal-open');
  }
  window.openAuthModal = openAuthModal;
  window.closeAuthModal = closeAuthModal;
  if(authModal){
    authModal.addEventListener('click', function(e){
      if(e.target === authModal) closeAuthModal();
    });
    var closeBtn = authModal.querySelector('.modal-close');
    if(closeBtn) closeBtn.addEventListener('click', closeAuthModal);
  }

  /* ---------- Tabs ---------- */
  function switchAuthTab(tab){
    document.querySelectorAll('.modal-tabs button').forEach(function(b){
      b.classList.toggle('active', b.getAttribute('data-tab') === tab);
    });
    document.querySelectorAll('.tab-pane').forEach(function(p){
      p.classList.toggle('active', p.getAttribute('data-pane') === tab);
    });
  }
  window.switchAuthTab = switchAuthTab;
  document.querySelectorAll('.modal-tabs button').forEach(function(b){
    b.addEventListener('click', function(){ switchAuthTab(b.getAttribute('data-tab')); });
  });

  /* ---------- ESC closes overlays ---------- */
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){ closeAuthModal(); closeDrawer(); }
  });

  /* ---------- Back to top ---------- */
  var toTop = document.getElementById('toTop');
  if(!toTop){
    toTop = document.createElement('button');
    toTop.id = 'toTop';
    toTop.setAttribute('aria-label','Back to top');
    toTop.innerHTML = '&uarr;';
    document.body.appendChild(toTop);
    toTop.addEventListener('click', function(){
      window.scrollTo({top:0, behavior:'smooth'});
    });
  }

  /* ---------- Ripple ---------- */
  document.querySelectorAll('.btn, .btn-get-started').forEach(function(btn){
    btn.addEventListener('click', function(e){
      var rect = btn.getBoundingClientRect();
      var r = document.createElement('span');
      r.className = 'ripple';
      r.style.left = (e.clientX - rect.left) + 'px';
      r.style.top  = (e.clientY - rect.top)  + 'px';
      btn.appendChild(r);
      setTimeout(function(){ r.remove(); }, 650);
    });
  });

  /* ---------- Logo fallback ---------- */
  document.querySelectorAll('.logo-img').forEach(function(img){
    img.addEventListener('error', function(){
      var span = document.createElement('span');
      span.className = 'logo-fallback';
      span.textContent = 'ST';
      img.parentNode.replaceChild(span, img);
    });
  });

  /* ---------- Animated stat numbers ---------- */
  function animateNumber(el, target, duration){
    var startTime = null;
    function step(t){
      if(!startTime) startTime = t;
      var p = Math.min((t - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      var val = Math.floor(target * eased);
      el.textContent = val.toLocaleString();
      if(p < 1) requestAnimationFrame(step);
      else el.textContent = Number(target).toLocaleString();
    }
    requestAnimationFrame(step);
  }
  var statVals = document.querySelectorAll('.stat-card .value[data-count]');
  if(statVals.length && 'IntersectionObserver' in window){
    var so = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting){
          var num = parseFloat(e.target.getAttribute('data-count'));
          if(!isNaN(num)) animateNumber(e.target, num, 1000);
          so.unobserve(e.target);
        }
      });
    }, {threshold:.4});
    statVals.forEach(function(v){ so.observe(v); });
  }

  /* ---------- Hero parallax ---------- */
  var hero = document.querySelector('.hero');
  if(hero && window.matchMedia('(min-width: 900px)').matches){
    hero.addEventListener('mousemove', function(e){
      var r = hero.getBoundingClientRect();
      var x = (e.clientX - r.left) / r.width - 0.5;
      var y = (e.clientY - r.top)  / r.height - 0.5;
      hero.querySelectorAll('.hero-orb').forEach(function(orb, i){
        var d = (i + 1) * 14;
        orb.style.transform = 'translate(' + (x*d) + 'px,' + (y*d) + 'px)';
      });
    });
  }

})();

/* ============================================================
   FEATURES - lightbox + wishlist toggle
   ============================================================ */
(function(){
  'use strict';

  /* ---------- Lightbox ---------- */
  window.openLightbox = function(src, caption){
    var lb = document.getElementById('lightbox');
    if (!lb) return;
    var img = lb.querySelector('.lightbox-img');
    var cap = lb.querySelector('.lightbox-caption');
    img.src = src;
    if (caption && caption.length) { cap.textContent = caption; cap.style.display = 'block'; }
    else { cap.style.display = 'none'; }
    lb.classList.add('open');
    document.body.classList.add('modal-open');
  };
  window.closeLightbox = function(){
    var lb = document.getElementById('lightbox');
    if (lb) lb.classList.remove('open');
    document.body.classList.remove('modal-open');
  };
  document.addEventListener('click', function(e){
    var lb = document.getElementById('lightbox');
    if (lb && lb.classList.contains('open')){
      if (e.target === lb || e.target.classList.contains('lightbox-close')){
        window.closeLightbox();
      }
    }
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') window.closeLightbox();
  });

  /* ---------- Wishlist toggle (AJAX) ---------- */
  document.addEventListener('click', function(e){
    var btn = e.target.closest && e.target.closest('.wish-btn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();

    var id = btn.getAttribute('data-product');
    if (!id) return;

    var wasActive = btn.classList.contains('active');
    btn.classList.toggle('active'); // optimistic

    fetch((window.SITE_URL || '') + '/wishlist_toggle.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'product_id=' + encodeURIComponent(id),
      credentials: 'same-origin'
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if (d.redirect){
        // Not logged in
        btn.classList.toggle('active', wasActive);
        window.location.href = d.redirect;
        return;
      }
      if (d.status === 'added') btn.classList.add('active');
      else btn.classList.remove('active');

      var hc = document.getElementById('wishlistCount');
      if (hc && typeof d.count !== 'undefined'){
        hc.textContent = d.count;
        hc.style.display = d.count > 0 ? '' : 'none';
      }
    })
    .catch(function(){
      btn.classList.toggle('active', wasActive);
    });
  });

})();