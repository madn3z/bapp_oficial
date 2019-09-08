<template>
  <div id="data-list-list-view" class="data-list-container">

  

    <vs-table ref="table" multiple v-model="selected" pagination :max-items="itemsPerPage" search :data="roles">

      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">

        <div class="flex flex-wrap-reverse items-center">

          <!-- ACTION - DROPDOWN -->
          <vs-dropdown vs-trigger-click  class="cursor-pointer mr-4 mb-4">

            <div class="p-4 shadow-drop rounded-lg d-theme-dark-bg cursor-pointer flex items-center justify-center text-lg font-medium w-32">
              <span class="mr-2">Acciones</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>

            <vs-dropdown-menu >
              <vs-dropdown-item @click.prevent="accion(1)">
                <span>Borrar</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click.prevent="accion(2)">
                <span>Exportar</span>
              </vs-dropdown-item>
              <!--<vs-dropdown-item @click.prevent="accion(3)">
                <span>Imprimir</span>
              </vs-dropdown-item>
               <vs-dropdown-item>
                <span>Another Action</span>
              </vs-dropdown-item>-->
            </vs-dropdown-menu>
          </vs-dropdown>


          <!-- ADD NEW -->
          <div class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base 
          text-primary border border-solid border-primary" @click="agregarPopUp" > <!-- @click="addNewDataSidebar = true" -->
              <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
              <vx-tooltip color="primary" text="Agregar rol">
              <span class="ml-2 text-base text-primary">Agregar Rol</span>
              </vx-tooltip>
          </div>
        </div>

        <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR ROL' : 'ACTUALIZAR ROL')"  :active.sync="popupActive"  @hidden="cancelarPopUp">
            <div class="p-5">
              <!-- NAME -->
              <vs-input v-validate="'required|alpha_dash'" label-placeholder="Nombre" name="name" v-model="rol.name" 
              :danger="(errors.first('name') ? true : false)" val-icon-danger="clear" class="mt-5 w-full" />
              <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

              <!-- PERMISOS
              <vs-select
                      multiple
                      class="selectExample"
                      label="Permisos"
                      v-model="select2"
                      >
                      <div :key="index" v-for="item,index in options1">
                        <vs-select-group :title="item.title" v-if="item.group">
                          <vs-select-item :key="index" :value="item.value" :text="item.text"  v-for="item,index in item.group"/>
                        </vs-select-group>
                      </div>
                  </vs-select> -->


               <div>
                <label class="typo__label" style="font-size: 12px;">Permisos</label>
                <multiselect v-model="rol.permisos" :options="options" :multiple="true" 
                group-values="roles" group-label="modulos" :group-select="true" v-validate="'required'" 
                track-by="id" label="name" :searchable="false" :show-labels="true" name="permisos" placeholder="Seleccione una opcion" 
                :close-on-select="false" >
                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" 
                v-if="values.length &amp;&amp; !isOpen">{{ values.length }} opciones seleccionadas</span></template>
                
                </multiselect>
                <span class="text-danger text-sm" v-show="errors.has('permisos')">{{ errors.first('permisos') }}</span>
              
                
              </div>   

              <!--<br/> 
              <div class="grid-layout-container alignment-block">
              <span class="text-sm">Permisos</span>
                <template>
                <v-tree  name="permisos" ref='tree' :canDeleteRoot="false" :data='treeData' 
                :draggable='false'  :halfcheck='true' :multiple="true"/>
              </template>
             
               <vs-row vs-align="flex-end" vs-type="flex" vs-justify="center" vs-w="12">
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                    Modulo
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Ver
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Crear
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Editar
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Eliminar
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Detalle
                    </vs-col>
                  </vs-row>
                  
                   <template>
              <v-select-tree :data='treeData' v-model='initSelected' :multiple="true"/>
            </template>
              </div>-->

             



           
            <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="submitFormActualizar" >ACTUALIZAR ROL</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="submitForm">AGREGAR ROL</vs-button>
              
           
              <vs-button type="border" color="danger" ref="btncancelar" @click.prevent="cancelarPopUp">CANCELAR</vs-button>
            </div>
            </div>
        </vs-popup>
        <!-- POP UP -->



        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ roles.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : roles.length }} of {{ roles.length }}</span>
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>
          <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
          <vs-dropdown-menu>

            <vs-dropdown-item @click="itemsPerPage=4">
              <span>4</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=10">
              <span>10</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=15">
              <span>15</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=20">
              <span>20</span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>

      <template slot="thead">
        <vs-th sort-key="roles-id">ID</vs-th>
        <vs-th sort-key="roles-name">Nombre</vs-th>
       <!--  <vs-th sort-key="roles-created_at">Creado</vs-th>
        <vs-th sort-key="roles-updated_at">Actualizado</vs-th>-->
        <vs-th sort-key="roles-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
          <tbody>
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="users-id font-medium">{{ tr.id }}</p>
              </vs-td>
               <vs-td>
                <p class="users-rol">{{ tr.name }}</p>
              </vs-td>
				       <!--  <vs-td>
                <p class="users-created_at">{{ tr.created_at }}</p>
              </vs-td>

              <vs-td>
                <p class="users-updated_at">{{ tr.updated_at }}</p>
              </vs-td>-->
              <vs-td>
                  <div class="demo-alignment" style="margin-top: -22px !important;">
                    <vx-tooltip color="primary" text="Editar">
                    <vs-button  color="primary"  type="border" @click="editar(tr)"  icon="edit"></vs-button>
                    </vx-tooltip>
                    <vx-tooltip color="primary" text="Eliminar">
                    <vs-button  color="primary"  type="border" @click="eliminar(tr, indextr)"  icon="delete_sweep"></vs-button>
                    </vx-tooltip>
                  </div>
            </vs-td>
            </vs-tr>
          </tbody>
        </template>
    </vs-table>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect'
export default {
  components: { Multiselect 
  },
  data() {
    return {
      selected: [],
      roles: [],
      itemsPerPage: 4,
      isMounted: false,
      ro : "",
      ind : "",
      popupActive: false,
      rol : {},
      modoEditar: false,      
      exportData : [],
      permisos : [],
      aux: 0,

      //select2:'',
      //options1:[],

       options: []

    }
  },
  computed: {
    currentPage() {
      if(this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
  },
  methods: {
    accion(seleccion) {
      switch (seleccion) {
        case 1:
            this.aux = this.selected.length;
            if(this.aux > 0){


                this.$vs.dialog({
                  
                  type: 'confirm',
                  color: 'danger',
                  title: `Borrado masivo`,
                  text: `Esta seguro que desea eliminar todos lo registros seleccionados?`,
                  accept: this.acceptAlertMasivo,
              
                })
              
              }else{

                this.$vs.dialog({
                    color:'danger',
                    title: 'Error',
                    text: 'Debe seleccionar al menos un registro para realizar esta accion.',
                     iconPack: 'feather',
                    icon:'icon-alert-circle'
                  })

              }
            break;
        case 2:
             //Arreglar datos excel
            const datos = this.roles;
            this.csvExport(datos);
            break;          
        default:
           return;
    }
    },
    acceptAlertMasivo() {
        const thisDelM = this;
        const registrosSeleccionados = this.selected;

        this.$http.post(`/roles/roles/borrar`, registrosSeleccionados)
          .then(()=>{
   
            thisDelM.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.refrescaTabla();
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             thisDelM.$vs.notify({
              title:'Exito',
              text: 'Los registros han sido eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})


          })
          .catch(function (error) {
            thisDelM.$vs.notify({
              title:'Error',
              text: error,
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})       
          });

   
     },
    refrescaTabla() {
         //Carga Rol
        const thisIns = this;
        this.$http.get('roles/roles')
          .then(function (response) {
              thisIns.roles = response.data.roles //thisIns.formatData(response.data.users) formatear data
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
     refrescaOtrosDatos() {
        //Carga Roles   
        const thisIns = this; 
        //thisIns.treeData = {};
        this.$http.get('permisos/permisos')
          .then(function (response) {
            //thisIns.permisos = response.data.permisos //thisIns.formatData(response.data.users) formatear data

             thisIns.options1 = response.data.permisos_aux
             thisIns.options = response.data.permisos_aux
            
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
    eliminar(rol, index) {
        this.ro = rol;
        this.ind = index;
    
        this.$vs.dialog({
                type: 'confirm',
                color: 'danger',
                title: `Eliminar rol`,
                text: `Esta seguro que desea eliminar el rol: ${rol.name} ?`,
                accept: this.acceptAlert,
                cancel: this.cancelAlert,
              
            })
        },
    cancelAlert() {
          delete this.ro;
          delete this.ind;
    },
    acceptAlert() {
      const thisEliminar = this;
        this.$http.delete(`/roles/roles/${this.ro.id}`)
          .then(()=>{
            this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.refrescaTabla();
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             thisEliminar.$vs.notify({
              title:'Exito',
              text: 'El rol ha sido eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.ro;
              delete this.ind;

          })

          .catch(function (error) {
            thisEliminar.$vs.notify({
              title:'Error',
              text: error,
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.ro;
              delete this.ind;
          });
     },
    editar(item){      
      this.initValues();
      this.modoEditar = true; 
      //console.log(item.roles[0].id);
      this.rol.name = item.name;
      this.rol.id = item.id;
      this.rol.permisos = item.permisos;
      this.popupActive=true;
    },
    cancelarPopUp(){

      this.initValues();
      this.popupActive=false;
      
    },
    agregarPopUp(){
  
      this.initValues();
      this.modoEditar = false; 
      this.popupActive=true;
      
    },

    initValues() {
      this.rol = {};
      this.errors.clear();
    },

    submitFormActualizar() {
      const thisInsAdd = this;
      this.$validator.validateAll().then(result =>{
        if (result) {
          const rolAct = this.rol;
        this.$http.put('roles/roles/' + this.rol.id, rolAct) 
            .then((res) =>{             
              
              this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.refrescaTabla();
              this.initValues();   
              this.modoEditar  = false;            
              this.popupActive = false;
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
        
              thisInsAdd.$vs.notify({
                title:'Exito',
                text: 'El rol se ha actualizado con exito!.',
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
        }
      })
    },
    submitForm() {

      const thisInsAdd = this;
      this.$validator.validateAll().then(result =>{
        if (result) {

          const rolNueva = this.rol;
         this.$http.post('roles/roles/store', rolNueva) 
            .then((res) =>{

              this.$vs.loading({ type: 'radius' , scale: 0.6 });
                this.refrescaTabla();
                this.initValues();
                this.modoEditar = false; 
                this.popupActive=false;
                setTimeout(() => {
                  this.$vs.loading.close()
                }, 500);

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
        }
      })
    },
    csvExport(arrData) {
      let csvContent = "data:text/csv;charset=utf-8,";
      csvContent += [
        Object.keys(arrData[0]).join(";"),
        ...arrData.map(item => Object.values(item).join(";"))
      ]
        .join("\n")
        .replace(/(^\[)|(\]$)/gm, "");

      const data = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", data);
      link.setAttribute("download", "export.csv");
      link.click();
    }
  },
  created() {
      this.refrescaTabla();
      this.refrescaOtrosDatos();
      
  },
  mounted() {
    this.isMounted = true;
  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss">
.selectExample {
  margin: 0px;
}
.con-select-example {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 10px;
}
.con-select .vs-select {
  width: 100%;
  margin: 20px;
}
@media (max-width: 550px) {
  .con-select {
    flex-direction: column;
  }
  .con-select .vs-select {
    width: 100%
  }
}
.multiselect__tags{
      border: 1px solid rgba(0, 0, 0, 0.2) !important;
      background: #10163a !important;
      font-size: 12px;
}

.multiselect__single {
     background: #10163a !important;
     color: #c2c6dc !important;
     font-size: 12px !important;
}

.multiselect__placeholder {
     color: #c2c6dc !important;
    font-size: 12px !important;
}


.multiselect--active {
  z-index: 50;
  
}
.multiselect__option {  
    cursor: pointer !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    transition: all .2s ease;
        font-size: 12px;
    
}


#data-list-list-view {
  .vs-con-table {

    .vs-table--header {
      display: flex;
      flex-wrap: wrap-reverse;
      margin-left: 1.5rem;
      margin-right: 1.5rem;
      > span {
        display: flex;
        flex-grow: 1;
      }

      .vs-table--search{
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          &+i {
            left: 1rem;
          }

          &:focus+i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr{
          box-shadow: 0 4px 20px 0 rgba(0,0,0,.05);
          td{
            padding: 20px;
            &:first-child{
              border-top-left-radius: .5rem;
              border-bottom-left-radius: .5rem;
            }
            &:last-child{
              border-top-right-radius: .5rem;
              border-bottom-right-radius: .5rem;
            }
          }
          td.td-check{
            padding: 20px !important;
          }
      }
    }

    .vs-table--thead{
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text{
          text-transform: uppercase;
          font-weight: 600;
        }
      }
      th.td-check{
        padding: 0 15px !important;
      }
      tr{
        background: none;
        box-shadow: none;
      }
    }

    .vs-table--pagination {
      justify-content: center;
    }
  }
}
</style>
