<template>
  <div id="data-list-list-view" class="data-list-container">

  

    <vs-table ref="table" multiple v-model="selected" pagination :max-items="itemsPerPage" search :data="users">

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
              <vx-tooltip color="primary" text="Agregar usuario">
              <span class="ml-2 text-base text-primary">Agregar Usuario</span>
              </vx-tooltip>
          </div>
        </div>

        <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR USUARIO' : 'ACTUALIZAR USUARIO')"  :active.sync="popupActive"  @hidden="cancelarPopUp">
        <div class="mt-5">
      <form-wizard color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" 
      :title="(modoEditar == false ? 'AGREGAR USUARIO' : 'ACTUALIZAR USUARIO')" 
      :subtitle="(modoEditar == false ? 'Ingrese todos los campos para ingresar el usuario' : 'Modifique los campos que desee actualizar')" 
      :finishButtonText="(modoEditar == false ? 'Agregar' : 'Actualizar')" ref="wizard">
        <tab-content title="Paso 1" class="mb-5" icon="feather icon-home" :before-change="validateStep1">

          <!-- tab 1 content -->
          <form data-vv-scope="step-1">
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-input label="Nombre" v-model="usuario.name" class="w-full" name="name" v-validate="'required|alpha'" 
             :danger="(errors.first('step-1.name') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger">{{ errors.first('step-1.name') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-input label="Apellido"  v-model="usuario.lastname" class="w-full" name="lastname" v-validate="'required|alpha'" 
              :danger="(errors.first('step-1.lastname') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger">{{ errors.first('step-1.lastname') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-input type="Email" label="Email"  v-model="usuario.email" class="w-full" name="email" v-validate="'required|email'" 
              :danger="(errors.first('step-1.email') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger">{{ errors.first('step-1.email') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
                <vs-input  label="Rut"  v-model="usuario.rut" class="w-full" name="rut" v-validate="'required|alpha_dash'" 
                :danger="(errors.first('step-1.rut') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger">{{ errors.first('step-1.rut') }}</span>
            </div>
          </div>
          </form>
        </tab-content>

        <!-- tab 2 content -->
        <tab-content title="Paso 2" class="mb-5" icon="feather icon-briefcase" :before-change="validateStep2">
          <form data-vv-scope="step-2">
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-select v-model="usuario.roles" label="Roles" name="roles" class="mt-5 w-full" v-validate="'required'" >
                <vs-select-item :key="item.id" :value="item.id" :text="item.name" v-for="item in roles_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('step-2.roles')">{{ errors.first('step-2.roles') }}</span>
            </div>
             <div class="vx-col md:w-1/2 w-full mt-5">
                     <vs-select v-model="usuario.empresa_id" label="Empresa" name="empresa" class="mt-5 w-full" v-validate="'required'" >
                <vs-select-item :key="item.id" :value="item.id" :text="item.razon_social" v-for="item in empresa_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('step-2.empresa')">{{ errors.first('step-2.empresa') }}</span>
          
             </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
                    <vs-input label="Telefono" v-model="usuario.telefono" class="mt-5 w-full" name="telefono" v-validate="'required|numeric'" 
              :danger="(errors.first('step-2.telefono') ? true : false)" val-icon-danger="clear"/>
              <span class="text-danger">{{ errors.first('step-2.telefono') }}</span>
             </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
                  <vs-input type="password" v-validate="(modoEditar == false ? 'required|min:8' : 'min:8')" ref="password" label="Password" name="password" 
                  v-model="usuario.password"  class="mt-5 w-full" 
                  :danger="(errors.first('step-2.password') ? true : false)" val-icon-danger="clear"/>
                  <span class="text-danger text-sm" v-show="errors.has('step-2.password')" >{{ errors.first('step-2.password') }}</span>
                </div>
              <div class="vx-col md:w-1/2 w-full mt-5">
                  <vs-input type="password" v-validate="(modoEditar == false ? 'required|min:8|confirmed:password' : 'min:8|confirmed:password')" data-vv-as="password" label="Confirmar Password" 
                  name="password_confirmation"  class="mt-5 w-full" v-model="usuario.password_confirmation"
                  :danger="(errors.first('step-2.password_confirmation') ? true : false)" val-icon-danger="clear"/>
                  <span class="text-danger text-sm" v-show="errors.has('step-2.password_confirmation')">{{ errors.first('step-2.password_confirmation') }}</span>
           
            </div>
         <div class="vx-col md:w-1 w-full">
          <div class="demo-alignment">
            <span>Habilitado:</span>
            <div class="flex">
              <!--<vs-checkbox v-model="usuario.habilitado" name="habilitado">Habilitado</vs-checkbox>-->
              <ul class="centerx">
               <li>
                <vs-radio color="success" v-model="usuario.habilitado" vs-value="1" >Activo</vs-radio>
              </li>
              <li>
                <vs-radio color="danger" v-model="usuario.habilitado" vs-value="0" >Inactivo</vs-radio>
              </li>
              </ul>
            </div>
          </div>
          </div>
          
          </div>
          </form>
        </tab-content>
      </form-wizard>
    </div>
                
        </vs-popup>
        <!-- POP UP -->

        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ users.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : users.length }} of {{ users.length }}</span>
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
        <vs-th sort-key="users-id">ID</vs-th>
        <vs-th sort-key="users-name">Nombre</vs-th>
        <vs-th sort-key="users-lastname">Apellido</vs-th>
        <vs-th sort-key="users-email">Email</vs-th>
        <vs-th sort-key="users-rol">Rol</vs-th>
		    <vs-th sort-key="users-status">Estado</vs-th>
       <!--  <vs-th sort-key="users-created_at">Creado</vs-th>
        <vs-th sort-key="users-updated_at">Actualizado</vs-th>-->
        <vs-th sort-key="users-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
          <tbody>
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="users-id font-medium">{{ tr.id }}</p>
              </vs-td>

              <vs-td>
                <p class="users-name">{{ tr.name }}</p>
              </vs-td>
              <vs-td>
                <p class="users-lastname">{{ tr.lastname }}</p>
              </vs-td>
              <vs-td>
                <p class="users-email">{{ tr.email }}</p>
              </vs-td>
              
              <vs-td>
                <p class="users-rol">{{ tr.roles[0].name }}</p>
              </vs-td>
				      <vs-td>
                <vs-chip :color="getStatusColor(tr.habilitado)" class="users-status">{{ tr.habilitado == 1 ? 'Activo' : 'Inactivo'  }}</vs-chip>
              </vs-td>
             <!--  <vs-td>
                <p class="users-created_at">{{ tr.created_at }}</p>
              </vs-td>

              <vs-td>
                <p class="users-updated_at">{{ tr.updated_at }}</p>
              </vs-td>-->
              <vs-td>
                  <div class="demo-alignment" style="margin-top: -22px !important;z-index:999;">
                    <template style="z-index:999;">   
                      <vx-tooltip color="primary" text="Editar">
                      <vs-button  color="primary"  type="border" @click="editar(tr)"  icon="edit"></vs-button>
                      </vx-tooltip>
                      <vx-tooltip color="primary" text="Eliminar">
                      <vs-button  color="primary"  type="border" @click="eliminar(tr, indextr)"  icon="delete_sweep"></vs-button>
                      </vx-tooltip>
                    </template>

                  </div>
            </vs-td>
            </vs-tr>
          </tbody>
        </template>
    </vs-table>
  </div>
</template>
<script>
import { FormWizard, TabContent } from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

// For custom error message
import { Validator } from 'vee-validate';
const dict = {
    custom: {
        name: {
            required: 'El nombre es requerido',
            alpha: "El nombre solo puede contener letras"
        },
        lastname: {
            required: 'El apellido es requerido',
            alpha: "El apellido solo puede contener letras"
        },
        rut: {
            required: 'El rut es requerido',
            alpha_dash: "Ingrese un rut valido"
        },
        email: {
            required: 'El email es requerido',
            email: "Ingrese un emil valido"
        },
        roles: {
            required: 'El rol es requerido',
        },
        empresa: {
            required: 'La empresa es requerido',
        },
        telefono: {
            required: 'El telefono es requerido',
            numeric: "El numero de telefono debe ser valido"
        },
        password: {
            required: 'La password es requerida',
            min: "La password debe tener minimo 8 caracteres",
        },
        password_confirmation: {
            required: 'Debe ingresar la confirmacion de la password',
            min: "La confirmacion de la password debe tener minimo 8 caracteres",
            confirmed: "Las password debe ser iguales"
        }
    }
};

// register custom messages
Validator.localize('en', dict);

export default {
  components: { 
     FormWizard,
        TabContent
  },
  data() {
    return {
      selected: [],
      users: [],
      itemsPerPage: 4,
      isMounted: false,
      usr : "",
      ind : "",
      popupActive: false,
      usuario : {},
      modoEditar: false,      
      roles_choices: [],
      empresa_choices: [],
      exportData : [],
      aux: 0,

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
    validateStep1() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll('step-1').then(result => {
                    if (result) {
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },
        validateStep2() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll("step-2").then(result => {
                    if (result) {
                      if(this.modoEditar == false){
                          this.submitForm();
                      }else{
                          this.submitFormActualizar();
                      }
                        
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },
    getStatusColor(status) {
      if(status == 1) return "success"
      if(status == 0) return "danger"
      return "danger"
    },
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
            const datos = this.users;
            this.csvExport(datos);
            break;          
        default:
           return;
    }
    },
    acceptAlertMasivo() {
        const thisDelM = this;
        const registrosSeleccionados = this.selected;

        this.$http.post(`/users/users/borrar`, registrosSeleccionados)
          .then(()=>{
            //this.users.splice(this.ind, 1);
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
         //Carga Usuario
        const thisIns = this;
        this.$http.get('users/users')
          .then(function (response) {
              thisIns.users = response.data.users //thisIns.formatData(response.data.users) formatear data
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
        //Carga Empresa
          this.$http.get('empresa/empresa')
          .then(function (response) {
            thisIns.empresa_choices = response.data.empresas //thisIns.formatData(response.data.users) formatear data
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
    eliminar(user, index) {
        this.usr = user;
        this.ind = index;
    
        this.$vs.dialog({
                type: 'confirm',
                color: 'danger',
                title: `Eliminar usuario`,
                text: `Esta seguro que desea eliminar al usuario: ${user.name} ?`,
                accept: this.acceptAlert,
                cancel: this.cancelAlert,
              
            })
        },
    cancelAlert() {
          delete this.usr;
          delete this.ind;
    },
    acceptAlert() {
        this.$http.delete(`/users/users/${this.usr.id}`)
          .then(()=>{
            //this.users.splice(this.ind, 1);
            this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.refrescaTabla();
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             this.$vs.notify({
              title:'Exito',
              text: 'El usuario ha sido eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.usr;
              delete this.ind;

          })
          .catch(function (error) {
            this.$vs.notify({
              title:'Error',
              text: error,
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.usr;
              delete this.ind;
          });
     },
    editar(item){      
      this.initValues();
      this.modoEditar = true; 
      //console.log(item.roles[0].id);
      this.usuario.empresa_id = item.empresa_id;
      this.usuario.email = item.email;
      this.usuario.name = item.name;
      this.usuario.lastname = item.lastname;
      this.usuario.rut = item.rut;
      this.usuario.telefono = item.telefono;
      this.usuario.habilitado = item.habilitado;
      this.usuario.roles = item.roles[0].id;
      this.usuario.id = item.id;
      //this.usuario = item;
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

      //this.$refs.wizard.navigateToTab(0);
      this.usuario = {};
      this.usuario.roles = '';
      this.usuario.empresa_id = '';
      this.errors.clear();
      this.$refs.wizard.reset();
      //this.modoEditar = false;    
    },

    submitFormActualizar() {
      const thisInsAdd = this;
      this.$validator.validateAll().then(result =>{
        if (result) {
          const usuarioAct = this.usuario;
        this.$http.put('users/users/' + this.usuario.id, usuarioAct) 
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
                text: 'El usuario se ha actualizado con exito!.',
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

          const usuarioNueva = this.usuario;
         this.$http.post('users/users/store', usuarioNueva) 
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

<style lang="scss">
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
