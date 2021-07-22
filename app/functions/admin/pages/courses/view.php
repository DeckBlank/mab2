<main id='app' data-site="<?php echo get_site_url(); ?>">
    <h2 class="mb-2">Importar categorias 📚▶️</h2>
    <p>Nota: Recuerde haber exportado las categorias del <b>maboffline</b></p>
    <div class="d-flex mb-2">
        <input type='file' @change='changeCoursesDatabase' accept=".json"/>
        <button :disabled="isLoading" class="button-primary" @click="importCoursesDatabase">Importar categorias</button>
    </div>
    <p><b>Proceso:</b> <span class="pri-color">{{step}}</span></p>
</main>
