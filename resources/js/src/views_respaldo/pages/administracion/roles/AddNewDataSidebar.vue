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
        <h4>AGREGAR NUEVO ROL</h4>
        <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
    </div>
    <vs-divider class="mb-0"></vs-divider>

    <VuePerfectScrollbar class="scroll-area--data-list-add-new pt-4 pb-6" :settings="settings">

      <div class="p-6">
        <!-- NAME -->
        <vs-input v-validate="'required|alpha_dash'" label="Nombre" name="name" v-model="rol.name" class="mt-5 w-full" />
        <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>
        
        <v-select-tree :data='treeData' v-model='initSelected' :multiple="true"/>

         </div>
    </VuePerfectScrollbar>

    <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
      <vs-button class="mr-6" @click.prevent="submitForm">AGREGAR ROL</vs-button>
      <vs-button type="border" color="danger" @click="isSidebarActiveLocal = false">CANCELAR</vs-button>
    </div>
  </vs-sidebar>
  
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { VTree, VSelectTree}  from 'vue-tree-halower'

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
      rol: {
          name: '',
      },
      roles: [],
      modoEditar: false,
      permisos_choices : [],         
      settings: { // perfectscrollbar settings
          maxScrollbarLength: 60,
          wheelSpeed: .60,
      },
      initSelected: ['node-1'],
      treeData: [{
        title: 'node1',
        expanded: true,
        children: [{
          title: 'node 1-1',
          expanded: true,
          children: [{
            title: 'node 1-1-1'
          }, {
            title: 'node 1-1-2'
          }, {
            title: 'node 1-1-3'
          }]
        }]
      }],
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
      delete this.rol.id;  
      delete this.rol.name;
       this.errors.clear();
           
    },
    submitForm() {
      const thisInsAdd = this;
      this.$validator.validateAll().then(result =>{
        if (result) {

          //console.log(this.usuario);
          // if form have no errors
          const rolNueva = this.rol;
          //this.nota = {nombre: '', descripcion: ''};   
        this.$http.post('roles/roles/store', rolNueva) 
          //axios.post('/notas', notaNueva)
            .then((res) =>{
              const nuevorol = res.data.rol;
              this.$parent.roles.push(nuevorol)
              
              this.initValues();
              this.$emit('closeSidebar');              

              thisInsAdd.$vs.notify({
                title:'Exito',
                text: 'El rol se ha creado con exito!.',
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
    this.$http.get('permisos/permisos')
      .then(function (response) {
		    thisIns.permisos_choices = response.data.permisos //thisIns.formatData(response.data.users) formatear data
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
    VTree,
    VSelectTree,
  }
}
</script>

<style lang="scss" scoped>
.tree-box{
  background: #fff;
  position: relative;
  z-index: 9;

  .search-input {
    margin-top: 10px;
    width: 98%;
    display: block;
  }
}

.rmNode{
  background-color: rgba(var(--vs-danger),0.15) !important;
color: rgba(var(--vs-danger),1) !important;
  line-height: 13px !important;
}
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
