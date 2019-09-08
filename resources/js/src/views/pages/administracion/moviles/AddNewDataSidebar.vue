<!-- =========================================================================================
  File Name: AddNewDataSidebar.vue
  Description: Add New Data - Sidebar component
  ----------------------------------------------------------------------------------------
  Item Name: Vuesax Admin - VueJS Dashboard Admin Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>

  <vs-sidebar click-not-close position-right parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
    <div class="mt-6 flex items-center justify-between px-6">
        <h4>AGREGAR NUEVO USUARIO</h4>
        <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
    </div>
    <vs-divider class="mb-0"></vs-divider>

    <VuePerfectScrollbar class="scroll-area--data-list-add-new pt-4 pb-6" :settings="settings">

      <div class="p-6">
        <!-- NAME -->
        <vs-input v-validate="'required|alpha_dash'" label="Nombre" name="name" v-model="usuario.name" class="mt-5 w-full" />
        <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

        <!-- EMAIL -->
        <vs-input v-validate="'required|email'" label="Email" name="email" v-model="usuario.email" class="mt-5 w-full" />
        <span class="text-danger text-sm" v-show="errors.has('email')">{{ errors.first('email') }}</span>

        <!-- ROLES -->
        <vs-select v-model="usuario.roles" label="Roles" name="roles" class="mt-5 w-full" v-validate="'required'" >
          <vs-select-item :key="item.id" :value="item.id" :text="item.name" v-for="item in roles_choices"  />
        </vs-select>
        <span class="text-danger text-sm" v-show="errors.has('roles')">{{ errors.first('roles') }}</span>

        <!-- PASSWORD -->
        <vs-input type="password" v-validate="'required|min:8'" ref="password" label="Password" name="password" v-model="usuario.password" class="mt-5 w-full" />
        <span class="text-danger text-sm" v-show="errors.has('password')">{{ errors.first('password') }}</span>
        
        <!-- CONFIRMAR PASSWORD -->
        <vs-input type="password" v-validate="'required|min:8|confirmed:password'" data-vv-as="password" label="Confirmar Password" name="password_confirmation" v-model="usuario.password_confirmation" class="mt-5 w-full" />
        <span class="text-danger text-sm" v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
   
      </div>
    </VuePerfectScrollbar>

    <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
      <vs-button class="mr-6" @click.prevent="submitForm">AGREGAR USUARIO</vs-button>
      <vs-button type="border" color="danger" @click="isSidebarActiveLocal = false">CANCELAR</vs-button>
    </div>
  </vs-sidebar>
  
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'

export default {
  props: {
    method: { type: Function },
    isSidebarActive: {
      type: Boolean,
      required: true
    },
  },
  data() {
    return {
      usuario: {
          name: '',
          roles: '',
          email: '',
          password: '',
          password_confirmation: '',
      },
      users: [],
      modoEditar: false,      
      roles_choices: [],      
      settings: { // perfectscrollbar settings
          maxScrollbarLength: 60,
          wheelSpeed: .60,
      },
    }
  },
  mounted() {
    // Instead of calling the method we emit an event
    this.$emit('send-message', this.modoEditar);
  },  
  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive
      },
      set(val) {
        if(!val) {          
          this.initValues();          
          this.$emit('closeSidebar');
        }
      }
    }
  },
  methods: {
    initValues() {
      delete this.usuario.id;  
      delete this.usuario.name;
      this.usuario.roles = '';
      delete this.usuario.email;
      delete this.usuario.password;
      delete this.usuario.password_confirmation;
      this.errors.clear();
           
    },
    submitForm() {
      const thisInsAdd = this;
      this.$validator.validateAll().then(result =>{
        if (result) {

          //console.log(this.usuario);
          // if form have no errors
          const usuarioNueva = this.usuario;
          //this.nota = {nombre: '', descripcion: ''};   
        this.$http.post('users/users/store', usuarioNueva) 
          //axios.post('/notas', notaNueva)
            .then((res) =>{
              const nuevoUser = res.data.user;
              this.$parent.users.push(nuevoUser)
              
              this.initValues();
              this.$emit('closeSidebar');              

              thisInsAdd.$vs.notify({
                title:'Exito',
                text: 'El usuario se ha creado con exito!.',
                color:'success',
                iconPack: 'feather',
                icon:'icon-alert-circle'}) 
                
                
                
            })
            .catch(function (error) {

                thisInsAdd.$vs.notify({
                  title:'Error',
                  text: error,
                  color:'danger',
                  iconPack: 'feather',
                  icon:'icon-alert-circle'})
            });
        } else {
          // form have errors
        }
      })
    }
  },
  created() {
    const thisIns = this;
    this.$http.get('roles/roles')
      .then(function (response) {
		    thisIns.roles_choices = response.data.roles //thisIns.formatData(response.data.users) formatear data
      })
      .catch(function (error) {
        thisIns.$vs.notify({
          title:'Error',
          text: error,
          color:'danger',
          iconPack: 'feather',
          icon:'icon-alert-circle'})
      });
  },
  components: {
    VuePerfectScrollbar,
  }
}
</script>

<style lang="scss" scoped>
.add-new-data-sidebar {
  ::v-deep .vs-sidebar--background {
    z-index: 52010;
  }

  ::v-deep .vs-sidebar {
    z-index: 52010;
    width: 400px;
    max-width: 90vw;

    .img-upload {
      margin-top: 2rem;

      .con-img-upload {
        padding: 0;
      }

      .con-input-upload {
        width: 100%;
        margin: 0;
      }
    }
  }
}

.scroll-area--data-list-add-new {
    height: calc(100% - 4.3rem);
}
</style>
