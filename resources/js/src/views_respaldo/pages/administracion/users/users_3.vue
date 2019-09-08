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
            <div class="p-5">
              <!-- NAME -->
              <vs-input v-validate="'required|alpha_dash'" label-placeholder="Nombre" name="name" v-model="usuario.name" class="mt-5 w-full" />
              <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

              <!-- EMAIL -->
              <vs-input v-validate="'required|email'" label-placeholder="Email" name="email" v-model="usuario.email" class="mt-5 w-full" />
              <span class="text-danger text-sm" v-show="errors.has('email')">{{ errors.first('email') }}</span>

              <!-- ROLES -->
              <vs-select v-model="usuario.roles" label="Roles" name="roles" class="mt-5 w-full" v-validate="'required'" >
                <vs-select-item :key="item.id" :value="item.id" :text="item.name" v-for="item in roles_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('roles')">{{ errors.first('roles') }}</span>

              <template v-if="modoEditar == false">
                  <!-- PASSWORD v-model="usuario.password"-->
                  <vs-input type="password" v-validate="'required|min:8'" ref="password" label-placeholder="Password" name="password" v-model="usuario.password"  class="mt-5 w-full" />
                  <span class="text-danger text-sm" v-show="errors.has('password')" >{{ errors.first('password') }}</span>
                  
                  <!-- CONFIRMAR PASSWORD v-model="usuario.password_confirmation"-->
                  <vs-input type="password" v-validate="'required|min:8|confirmed:password'" data-vv-as="password" label-placeholder="Confirmar Password" 
                  name="password_confirmation"  class="mt-5 w-full" v-model="usuario.password_confirmation"/>
                  <span class="text-danger text-sm" v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
            </template>
            <template v-else>
                    <!-- PASSWORD v-model="usuario.password"-->
                  <vs-input type="password" v-validate="'min:8'" ref="password" label-placeholder="Password" name="password" v-model="usuario.password"  class="mt-5 w-full" />
                  <span class="text-danger text-sm" v-show="errors.has('password')" >{{ errors.first('password') }}</span>
                  
                  <!-- CONFIRMAR PASSWORD v-model="usuario.password_confirmation"-->
                  <vs-input type="password" v-validate="'min:8|confirmed:password'" data-vv-as="password" label-placeholder="Confirmar Password" 
                  name="password_confirmation"  class="mt-5 w-full" v-model="usuario.password_confirmation"/>
                  <span class="text-danger text-sm" v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
              </template>
              
        
            
            <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="submitFormActualizar" >ACTUALIZAR USUARIO</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="submitForm">AGREGAR USUARIO</vs-button>
              
           
              <vs-button type="border" color="danger" ref="btncancelar" @click.prevent="cancelarPopUp">CANCELAR</vs-button>
            </div>
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
                <p class="users-email">{{ tr.email }}</p>
              </vs-td>
              
              <vs-td>
                <p class="users-rol">{{ tr.roles[0].name }}</p>
              </vs-td>
				      <vs-td>
                <vs-chip :color="getStatusColor(tr.id)" class="users-status">{{ tr.name }}</vs-chip>
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

export default {
  components: { 
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
    getStatusColor(status) {
      if(status == '1') return "success"
      if(status == '2') return "danger"
      return "primary"
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
      this.usuario.email = item.email;
      this.usuario.name = item.name;
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
      //delete this.usuario.id;  
     // delete this.usuario.name;
     // this.usuario.roles = '';
      //delete this.usuario.email;
      //delete this.usuario.password;
     // delete this.usuario.password_confirmation;
      this.usuario = {};
      this.usuario.roles = '';
      this.errors.clear();
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
