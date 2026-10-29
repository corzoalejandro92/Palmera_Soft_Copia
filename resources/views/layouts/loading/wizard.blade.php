<style>


    .loading[data-show='false'] {
        display: none;
    }

    .loading[data-show='true'] {
        display: block;
        left: 0;
    }

    .div-loading-text{
        position: absolute;
        width: 100%;
        background: #fff;
        z-index: 1000                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              ;
        height: 100%;
    }


    .div-loading-text > div > img, .div-loading-text > div > p{

        top: 25%;

    }

</style>
<div class="loading text-center div-loading-text" align="center" data-show="false" style="background: #fff;width: 100%;height: 100%;z-index: 10000000;">
    <div style="margin-top: 0px!important;">
        <img src="http://localdev-json.com/PALMERAJUNIOR/images/loading.gif">
    </div>
    <div class="mt-2">
        <p>Cargando...</p>
    </div>
</div>