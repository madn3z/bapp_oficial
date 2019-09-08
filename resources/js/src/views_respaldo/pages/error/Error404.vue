<!-- =========================================================================================
    File Name: Error404.vue
    Description: 404 Page
    ----------------------------------------------------------------------------------------
    Item Name: Vuesax Admin - VueJS Dashboard Admin Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
    <div class="h-screen flex w-full bg-img">
        <div class="vx-col flex items-center justify-center flex-col sm:w-1/2 md:w-3/5 lg:w-3/4 xl:w-1/2 mx-auto text-center">
            <img src="@assets/images/pages/404.png" alt="graphic-404" class="mx-auto mb-4 max-w-full">
            <h1 class="sm:mx-0 mx-4 mb-12 text-5xl d-theme-heading-color">404 - Error al ingresar a esta pagina!</h1>
            <p class="sm:mx-0 mx-4 mb-4 sm:mb-16 d-theme-text-inverse">1.- Usted no esta autorizado para ver esta pagina.</p>
            <p class="sm:mx-0 mx-4 mb-4 sm:mb-16 d-theme-text-inverse">2.- La pagina no existe o no se encuentra disponible.</p>
            <vs-button size="large" to="/">Volver al inicio</vs-button>
        </div>
    </div>
</template>
