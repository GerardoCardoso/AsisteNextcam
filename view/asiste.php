<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <img src="resources/images/nextcam_logo.png" width="19%"> <br/>
        <span><b>Asiste</b> Nextcam</span>
    </div>
    <div class="lockscreen-item">
        <form autocomplete="asistencia" id="asiste-formulario">
            <div class="input-group">
                <input class="form-control" type="number" name="codigopersona" placeholder="ID de asistencia" autocomplete="asistencia" autofocus required>
                <div class="input-group-btn">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-arrow-right text-muted" style="color:whitesmoke;"></i>
                    </button>
                </div>
            </div>
        </form>
        <div id="asiste-usuario" style="display:none;">
            <i class="fa fa-user"></i>
            <small id="text"></small>
        </div>
    </div>
    <div class="help-block text-center text-idasistencia">
        Ingresa tu ID de asistencia
    </div>
    <div class="text-center">
        <?php include('view/clock.html'); ?>
    </div>
</div>

<script src="resources/js/asiste.js"></script>
<script src="resources/js/preview.js"></script>