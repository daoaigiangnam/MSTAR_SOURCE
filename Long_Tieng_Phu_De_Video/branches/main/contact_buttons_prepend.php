<?php
declare(strict_types=1);

/*
 * Shared Call / Zalo / Email buttons for normal HTML pages.
 *
 * IMPORTANT:
 * auto_prepend_file also runs before AJAX/API PHP scripts. Never output
 * HTML/CSS/JS for those requests because the API response must remain
 * pure JSON, audio, or another binary response.
 */
$vdRequestUri = (string)($_SERVER['REQUEST_URI'] ?? '');
$vdScriptName = (string)($_SERVER['SCRIPT_NAME'] ?? '');
$vdScriptFile = (string)($_SERVER['SCRIPT_FILENAME'] ?? '');
$vdScriptBase = basename($vdScriptFile !== '' ? $vdScriptFile : $vdScriptName);
$vdAction = isset($_GET['action']) ? (string)$_GET['action'] : '';

$vdIsApiRequest =
    $vdScriptBase === 'api.php' ||
    strpos($vdRequestUri, '/ajax/') !== false ||
    strpos($vdScriptName, '/ajax/') !== false ||
    $vdAction !== '';

if ($vdIsApiRequest || PHP_SAPI === 'cli') {
    return;
}

register_shutdown_function(static function (): void {
    echo <<<'HTML'
<!-- Fixed contact buttons -->
<style id="videodubing-contact-buttons-css">
.vd-contact-float{
  position:fixed!important;
  top:auto!important;
  right:18px!important;
  bottom:18px!important;
  left:auto!important;
  z-index:2147483647!important;
  display:flex!important;
  flex-direction:column!important;
  gap:10px!important;
  align-items:flex-end!important;
  width:auto!important;
  height:auto!important;
  margin:0!important;
  padding:0!important;
  box-sizing:border-box!important;
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif!important;
  transform:none!important;
  visibility:visible!important;
  opacity:1!important;
  overflow:visible!important;
  pointer-events:auto!important;
}
.vd-contact-actions{
  display:flex!important;
  flex-direction:column!important;
  gap:10px!important;
  align-items:flex-end!important;
  width:auto!important;
  height:auto!important;
  overflow:visible!important;
}
.vd-contact-btn{
  width:48px!important;
  height:48px!important;
  min-width:48px!important;
  min-height:48px!important;
  flex:0 0 48px!important;
  border-radius:50%!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  text-decoration:none!important;
  color:#fff!important;
  font-size:21px!important;
  font-weight:900!important;
  line-height:1!important;
  box-shadow:0 10px 25px rgba(23,32,51,.18)!important;
  border:2px solid rgba(255,255,255,.9)!important;
  transition:transform .2s ease,box-shadow .2s ease!important;
  position:relative!important;
  box-sizing:border-box!important;
  overflow:visible!important;
  visibility:visible!important;
  opacity:1!important;
}
.vd-contact-btn:hover{
  transform:translateY(-3px) scale(1.04)!important;
  box-shadow:0 14px 30px rgba(23,32,51,.25)!important;
}
.vd-contact-btn span{
  position:absolute!important;
  right:58px!important;
  top:50%!important;
  transform:translate(6px,-50%)!important;
  opacity:0!important;
  pointer-events:none!important;
  white-space:nowrap!important;
  padding:7px 11px!important;
  border-radius:9px!important;
  background:#172033!important;
  color:#fff!important;
  font-size:11px!important;
  font-weight:800!important;
  line-height:1.2!important;
  z-index:2147483647!important;
}
.vd-contact-btn:hover span{
  opacity:1!important;
  transform:translate(0,-50%)!important;
}
.vd-call{background:#22a06b!important}.vd-zalo{background:#168cff!important}.vd-mail{background:#6947e8!important}

@media(max-width:600px){
  body{padding-bottom:78px!important;}
  .vd-contact-float{
    position:fixed!important;
    top:auto!important;
    right:0!important;
    bottom:0!important;
    left:0!important;
    width:100vw!important;
    max-width:100vw!important;
    height:68px!important;
    min-height:68px!important;
    margin:0!important;
    padding:6px 0 max(5px,env(safe-area-inset-bottom))!important;
    box-sizing:border-box!important;
    background:#fff!important;
    border-top:1px solid #e1e5ec!important;
    box-shadow:0 -5px 20px rgba(23,32,51,.12)!important;
    display:block!important;
    overflow:visible!important;
    transform:none!important;
    visibility:visible!important;
    opacity:1!important;
  }
  .vd-contact-actions{
    position:relative!important;
    width:100%!important;
    height:100%!important;
    min-height:56px!important;
    display:flex!important;
    flex-direction:row!important;
    gap:0!important;
    align-items:stretch!important;
    justify-content:space-around!important;
  }
  .vd-contact-btn{
    width:33.333333%!important;
    height:100%!important;
    min-width:0!important;
    min-height:56px!important;
    flex:1 1 33.333333%!important;
    border:0!important;
    border-radius:0!important;
    display:flex!important;
    flex-direction:column!important;
    align-items:center!important;
    justify-content:center!important;
    gap:3px!important;
    box-shadow:none!important;
    color:#344054!important;
    background:transparent!important;
    font-size:24px!important;
    line-height:1!important;
    transform:none!important;
    position:relative!important;
    overflow:visible!important;
  }
  .vd-contact-btn span{
    position:static!important;
    display:block!important;
    opacity:1!important;
    transform:none!important;
    padding:0!important;
    margin:0!important;
    background:transparent!important;
    color:#475467!important;
    font-size:11px!important;
    line-height:14px!important;
    font-weight:750!important;
    pointer-events:auto!important;
    white-space:nowrap!important;
  }
  .vd-contact-btn:hover{transform:none!important;box-shadow:none!important;}
  .vd-call,.vd-zalo,.vd-mail{background:transparent!important;}
  .vd-call{color:#22a06b!important}.vd-zalo{color:#168cff!important}.vd-mail{color:#6947e8!important}
}
</style>
<div class="vd-contact-float" id="vdContactFloat" aria-label="Liên hệ nhanh">
  <div class="vd-contact-actions">
    <a class="vd-contact-btn vd-call" href="tel:0963731910" aria-label="Gọi điện 0963731910"><b>☎</b><span>Gọi điện</span></a>
    <a class="vd-contact-btn vd-zalo" href="https://zalo.me/0963731910" target="_blank" rel="noopener" aria-label="Zalo 0963731910"><b>Z</b><span>Zalo</span></a>
    <a class="vd-contact-btn vd-mail" href="mailto:hotro@review360.id.vn" aria-label="Email hotro@review360.id.vn"><b>✉</b><span>Email</span></a>
  </div>
</div>
<script>
(function(){
  function pinContactBar(){
    var bar=document.getElementById('vdContactFloat');
    if(!bar) return;
    if(bar.parentNode!==document.documentElement){
      document.documentElement.appendChild(bar);
    }
    bar.style.setProperty('position','fixed','important');
    bar.style.setProperty('top','auto','important');
    bar.style.setProperty('right',window.innerWidth <= 600 ? '0' : '18px','important');
    bar.style.setProperty('bottom','0','important');
    bar.style.setProperty('left',window.innerWidth <= 600 ? '0' : 'auto','important');
    bar.style.setProperty('width',window.innerWidth <= 600 ? '100vw' : 'auto','important');
    bar.style.setProperty('transform','none','important');
    bar.style.setProperty('visibility','visible','important');
    bar.style.setProperty('opacity','1','important');
    bar.style.setProperty('z-index','2147483647','important');
  }
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',pinContactBar);
  else pinContactBar();
  window.addEventListener('load',pinContactBar,{passive:true});
  window.addEventListener('resize',pinContactBar,{passive:true});
  window.addEventListener('orientationchange',pinContactBar,{passive:true});
})();
</script>
<!-- /Fixed contact buttons -->
HTML;
});
