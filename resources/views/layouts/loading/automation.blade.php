<style>

#animationWindow {
 width: 50%;
 height: 50%;
 position: relative;
 top: -92px;
}

p.loading-container-text {
    margin-top: -213px;
    margin-bottom: 1rem;
    font-family: sans-serif;
    font-weight: 500;
    font-size: 16px;
    color: #acabab;
}
 p.loading-container-error, p.loading-container-back{
    margin-bottom: 1rem;
    font-family: sans-serif;
    font-weight: 500;
    font-size: 16px;
    color: #acabab;
}

</style>

<div class="loading-container" style="display:none;">
    <div id="animationWindow">
    </div>
</div>
<script src="<?=url('/')?>/template/app-assets/vendors/js/miscellaneous/bodymin.min.js"></script>
<script>
        document.getElementById('animationWindow').innerHTML = "";
        var animData = {
        wrapper: document.getElementById('animationWindow'),
        animType: 'svg',
        loop: true,
        prerender: true,
        autoplay: true,
        path: '{{url("resources/views/layouts/loading/")}}/lego_loader.json'
	};
	var anim = bodymovin.loadAnimation(animData);
    anim.setSpeed(3.4);

    
</script>

<p style="width: 312px; display:none;" class="loading-container-text">Estamos calculando las asignaciones mas precisas para estos servicios...</p>
<p style="width: 312px; display:none;" class="loading-container-error">Se produjo un problema, por favor vuelve a intentarlo.</p>
<p style="width: 312px; display:none;" class="loading-container-back">Se ha generado la automatización correctamente.</p>
