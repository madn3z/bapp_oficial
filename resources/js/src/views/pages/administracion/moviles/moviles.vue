<template>
  <div id="data-list-list-view" class="data-list-container">
    <vs-table
      ref="table"
      multiple
      v-model="selected"
      pagination
      :max-items="itemsPerPage"
      search
      :data="items"
    >
      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
        <div class="flex flex-wrap-reverse items-center">
          <!-- ACTION - DROPDOWN -->
          <vs-dropdown vs-trigger-click class="cursor-pointer mr-4 mb-4">
            <div
              class="p-4 shadow-drop rounded-lg d-theme-dark-bg cursor-pointer flex items-center justify-center text-lg font-medium w-32"
            >
              <span class="mr-2">Acciones</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>

            <vs-dropdown-menu>
              <vs-dropdown-item @click.prevent="$accion(1)">
                <span>Borrar</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click.prevent="$accion(2)">
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
          <div
            class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base text-primary border border-solid border-primary"
            @click="$agregarPopUp()"
          >
            <!-- @click="addNewDataSidebar = true" -->
            <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
            <vx-tooltip color="primary" text="Agregar conductor">
              <span class="ml-2 text-base text-primary">Agregar Conductor</span>
            </vx-tooltip>
          </div>
        </div>

        <!-- POP UP -->
        <vs-popup
          fullscreen
          class="holamundo"
          ref="modal"
          :title="(modoEditar == false ? 'AGREGAR CONDUCTOR' : 'ACTUALIZAR CONDUCTOR')"
          :active.sync="popupActive"
          @hidden="$cancelarPopUp()"
        >
          <div class="mt-5">
            <form-wizard
              color="rgba(var(--vs-primary), 1)"
              errorColor="rgba(var(--vs-danger), 1)"
              :title="(modoEditar == false ? 'AGREGAR CONDUCTOR' : 'ACTUALIZAR CONDUCTOR')"
              :subtitle="(modoEditar == false ? 'Ingrese todos los campos para ingresar el conductor' : 'Modifique los campos que desee actualizar')"
              :finishButtonText="(modoEditar == false ? 'Agregar' : 'Actualizar')"
              ref="wizard"
            >
			  <!-- tab 1 content -->
			  <tab-content
                title="Datos Conductor"
                class="mb-5"
                icon="feather icon-home"
                :before-change="validateStep1"
              >
                <form data-vv-scope="step-1">
                  <div class="vx-row">
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Nombres"
                        v-model="user.name"
                        class="w-full"
                        name="name"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.name') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.name') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Apellidos"
                        v-model="user.lastname"
                        class="w-full"
                        name="lastname"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.lastname') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.lastname') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        type="Email"
                        label="Email"
                        v-model="user.email"
                        class="w-full"
                        name="email"
                        v-validate="'required|email'"
                        :danger="(errors.first('step-1.email') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.email') }}</span>
                    </div>
					<div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        type="Telefono"
                        label="Telefono"
                        v-model="user.telefono"
                        class="w-full"
                        name="telefono"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('step-1.telefono') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.telefono') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Rut"
                        v-model="user.rut"
                        class="w-full"
                        name="rut"
                        v-validate="'required|alpha_dash'"
                        :danger="(errors.first('step-1.rut') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.rut') }}</span>
                    </div>
					<div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Ciudad"
                        v-model="driver.ciudad"
                        class="w-full"
                        name="ciudad"  
						v-validate="'required'"
                        :danger="(errors.first('step-1.ciudad') ? true : false)"
                        val-icon-danger="clear"                      
                      />
                      <span class="text-danger">{{ errors.first('step-1.ciudad') }}</span>
                    </div>
					<div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Comuna"
                        v-model="driver.comuna"
                        class="w-full"
                        name="comuna"  
						v-validate="'required'"
                        :danger="(errors.first('step-1.comuna') ? true : false)"
                        val-icon-danger="clear"                      
                      />
                      <span class="text-danger">{{ errors.first('step-1.comuna') }}</span>
                    </div>
					<div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Dirección"
                        v-model="driver.direccion"
                        class="w-full"
                        name="direccion"  
						v-validate="'required'"
                        :danger="(errors.first('step-1.direccion') ? true : false)"
                        val-icon-danger="clear"                      
                      />
                      <span class="text-danger">{{ errors.first('step-1.direccion') }}</span>
                    </div>
					<div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Numeración"
                        v-model="driver.numeracion"
                        class="w-full"
                        name="numeracion"  
						v-validate="'required'"
                        :danger="(errors.first('step-1.numeracion') ? true : false)"
                        val-icon-danger="clear"                      
                      />
                      <span class="text-danger">{{ errors.first('step-1.numeracion') }}</span>
                    </div>
                  </div>
                </form>
              </tab-content>



			  <!-- tab 2 content -->
              <tab-content
                title="Datos Movil"
                class="mb-5"
                icon="feather icon-directions"
                :before-change="validateStep2"
              >
                <form data-vv-scope="step-2">
                  <div class="vx-row">
					<div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Tipo Vehículo"
                        v-model="car.tipo"
                        class="w-full"
                        name="tipo"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.tipo') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.tipo') }}</span>
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
          <div
            class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
          >
            <span
              class="mr-2"
            >{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ items.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : items.length }} of {{ items.length }}</span>
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
        <vs-th sort-key="items-id">ID</vs-th>
        <vs-th sort-key="items-name">Nombre</vs-th>
        <vs-th sort-key="items-lastname">Apellido</vs-th>
        <vs-th sort-key="items-email">Email</vs-th>
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

      <template slot-scope="{data}">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              <p class="items-id font-medium">{{ tr.id }}</p>
            </vs-td>

            <vs-td>
              <p class="items-name">{{ tr.name }}</p>
            </vs-td>
            <vs-td>
              <p class="items-lastname">{{ tr.lastname }}</p>
            </vs-td>
            <vs-td>
              <p class="items-email">{{ tr.email }}</p>
            </vs-td>
            <vs-td>
              <vs-chip
                :color="getStatusColor(tr.habilitado)"
                class="items-status"
              >{{ tr.habilitado == 1 ? 'Activo' : 'Inactivo' }}</vs-chip>
            </vs-td>
            <!--  <vs-td>
                <p class="users-created_at">{{ tr.created_at }}</p>
              </vs-td>

              <vs-td>
                <p class="users-updated_at">{{ tr.updated_at }}</p>
            </vs-td>-->
            <vs-td>
              <div class="flex vx-col w-full sm:w-auto ml-auto mt-2 sm:mt-0">
                <vx-tooltip color="primary" text="Editar">
                  <vs-button
                    radius
                    color="primary"
                    type="border"
                    icon-pack="feather"
                    icon="icon-edit-2"
                    size="small"
                    class="ml-3"
                    @click="editar(tr)"
                  ></vs-button>
                </vx-tooltip>
                <vx-tooltip color="primary" text="Eliminar">
                  <vs-button
                    radius
                    color="primary"
                    type="border"
                    icon-pack="feather"
                    icon="icon-trash"
                    size="small"
                    class="ml-3"
                    @click="$submitEliminar(tr, indextr)"
                  ></vs-button>
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
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";

// For custom error message
import { Validator } from "vee-validate";
const dict = {
  custom: {
    name: {
      required: "El nombre es requerido",
      alpha: "El nombre solo puede contener letras"
    },
    lastname: {
      required: "El apellido es requerido",
      alpha: "El apellido solo puede contener letras"
    },
    rut: {
      required: "El rut es requerido",
      alpha_dash: "Ingrese un rut valido"
	},
    ciudad: {
      required: "La ciudad es requerida",
      email: "Ingrese una ciudad valida"
	}, 
	comuna: {
      required: "La comuna es requerida",
      email: "Ingrese una comuna valida"
	}, 
	direccion: {
      required: "La dirección es requerida",
      email: "Ingrese una dirección valida"
	},
    numeracion: {
      required: "La numeración es requerida",
      email: "Ingrese una numeración valida"
	},
    email: {
      required: "El email es requerido",
      email: "Ingrese un emil valido"
    },    
    telefono: {
      required: "El telefono es requerido",
      numeric: "El numero de telefono debe ser valido"
	},
	tipo: {
      required: "El tipo es requerido",
      numeric: "El numero de telefono debe ser valido"
    },
    
  }
};

// register custom messages
Validator.localize("en", dict);

export default {
  components: {
    FormWizard,
    TabContent
  },
  data() {
    return {
      ruta: "/driver/driver/",
      selected: [],
      items: [],
      itemsPerPage: 4,
      isMounted: false,
      ite: "",
      ind: "",
      popupActive: false,
	  item: {},
	  driver: {},
	  car: {},
	  user: {},
      modoEditar: false,
      roles_choices: [],
      empresa_choices: [],
      exportData: [],
      aux: 0
    };
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx;
      }
      return 0;
    }
  },
  methods: {
    validateStep1() {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll("step-1").then(result => {
          if (result) {
            resolve(true);
          } else {
            reject("correct all values");
          }
        });
      });
    },
    validateStep2() {
	  return new Promise((resolve, reject) => {
        this.$validator.validateAll("step-2").then(result => {
		  if (result) {
            if (this.modoEditar == false) {
			  this.item.driver = this.driver;
			  this.item.user = this.user; 
			  this.item.car = this.car;
			  this.$submitAgregar();
			  	
			} else {
              this.$submitActualizar();
            }

            resolve(true);
          } else {
            reject("correct all values");
          }
        });
      });
    },
    getStatusColor(status) {
      if (status == 1) return "success";
      if (status == 0) return "danger";
      return "danger";
    },
    refrescaOtrosDatos() {
      //Carga Roles
      const thisIns = this;
      this.$http
        .get("roles/roles")
        .then(function(response) {
          thisIns.roles_choices = response.data.items; //thisIns.formatData(response.data.users) formatear data
        })
        .catch(function(error) {
          thisIns.$vs.notify({
            title: "Error",
            text: error,
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-circle"
          });
        });
      //Carga Empresa
      this.$http
        .get("empresa/empresa")
        .then(function(response) {
          thisIns.empresa_choices = response.data.items; //thisIns.formatData(response.data.users) formatear data
        })
        .catch(function(error) {
          thisIns.$vs.notify({
            title: "Error",
            text: error,
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-circle"
          });
        });
    },
    editar(item) {
      this.initValues();
      this.modoEditar = true;
     /* this.item.email = item.email;
      this.item.name = item.name;
      this.item.lastname = item.lastname;
      this.item.rut = item.rut;
      this.item.telefono = item.telefono;
      this.item.habilitado = item.habilitado;
      this.item.roles = item.roles[0].id;
      this.item.id = item.id;*/
      //this.usuario = item;
      this.popupActive = true;
    },

    initValues() {
      //this.$refs.wizard.navigateToTab(0);
	  this.item = {};
	  this.car = {};
	  this.user = {};
      this.errors.clear();
      this.$refs.wizard.reset();
      //this.modoEditar = false;
	},
	successUpload(){
      this.$vs.notify({color:'success',title:'Upload Success',text:'Lorem ipsum dolor sit amet, consectetur'})
    }
  },
  created() {
    this.$refrescaTabla();
    this.refrescaOtrosDatos();
  },
  mounted() {
    this.isMounted = true;
  }
};
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

      .vs-table--search {
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          & + i {
            left: 1rem;
          }

          &:focus + i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr {
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
        td {
          padding: 20px;
          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }
          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
          }
        }
        td.td-check {
          padding: 20px !important;
        }
      }
    }

    .vs-table--thead {
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text {
          text-transform: uppercase;
          font-weight: 600;
        }
      }
      th.td-check {
        padding: 0 15px !important;
      }
      tr {
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

