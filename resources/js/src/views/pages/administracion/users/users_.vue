<template>
  <div id="data-list-list-view" class="data-list-container">

    <add-new-data-sidebar :isSidebarActive="addNewDataSidebar" @closeSidebar="addNewDataSidebar = false" />

    <vs-table ref="table" multiple v-model="selected" pagination :max-items="itemsPerPage" search :data="users">

      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">

        <div class="flex flex-wrap-reverse items-center">

          <!-- ACTION - DROPDOWN -->
          <vs-dropdown vs-trigger-click class="cursor-pointer mr-4 mb-4">

            <div class="p-4 shadow-drop rounded-lg d-theme-dark-bg cursor-pointer flex items-center justify-center text-lg font-medium w-32">
              <span class="mr-2">Acciones</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>

            <vs-dropdown-menu>

              <vs-dropdown-item>
                <span>Borrar</span>
              </vs-dropdown-item>
              <vs-dropdown-item>
                <span>Imprimir</span>
              </vs-dropdown-item>
              <vs-dropdown-item>
                <span>Exportar</span>
              </vs-dropdown-item>
              <!-- <vs-dropdown-item>
                <span>Another Action</span>
              </vs-dropdown-item>-->
            </vs-dropdown-menu>
          </vs-dropdown>

          <!-- ADD NEW -->
          <div class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base text-primary border border-solid border-primary" @click="addNewDataSidebar = true">
              <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
              <span class="ml-2 text-base text-primary">Agregar Usuario</span>
          </div>
        </div>

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
        <vs-th sort-key="id">ID</vs-th>
        <vs-th sort-key="name">Nombre</vs-th>
        <vs-th sort-key="email">Email</vs-th>
		    <vs-th sort-key="users-status">Estado</vs-th>
        <vs-th sort-key="created_at">Creado</vs-th>
        <vs-th sort-key="updated_at">Actualizado</vs-th>
        <vs-th sort-key="accion">Accion</vs-th>
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
                <vs-chip :color="getStatusColor(tr.id)" class="users-status">{{ tr.name }}</vs-chip>
              </vs-td>
              <vs-td>
                <p class="users-created_at">{{ tr.created_at }}</p>
              </vs-td>

              <vs-td>
                <p class="users-updated_at">{{ tr.updated_at }}</p>
              </vs-td>
              <vs-td>
                  <div class="demo-alignment" style="margin-top: -22px !important;">
                    <vs-button  color="primary"  type="border" @click="editarUser(tr)"  icon="edit"></vs-button>
                    <vs-button  color="primary"  type="border" @click="eliminarUsuario(tr, indextr)"  icon="delete_sweep"></vs-button>
                  </div>
            </vs-td>
            </vs-tr>
          </tbody>
        </template>
    </vs-table>
  </div>
</template>
<script>
import AddNewDataSidebar from './AddNewDataSidebar.vue';

export default {
  components: {
    AddNewDataSidebar
  },
  data() {
    return {
      selected: [],
      users: [],
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      usr : "",
      ind : "",
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
    handleSendMessage(event, value) {
      // Our event handler gets the event, as well as any
      // arguments the child passes to the event
      console.log('From the child:', value);
    },
    getStatusColor(status) {
      if(status == '1') return "success"
      if(status == '2') return "danger"
      return "primary"
    },
    formatData(data) {
      // formats data received from API
      let formattedData = data.map((item) => {
        const fields = item.fields
        let obj = {}
        for (const key of Object.keys(fields)) {
            obj[key] = Number(fields[key].integerValue) || Number(fields[key].doubleValue) || fields[key].stringValue;
        }
        return obj
      });
      return formattedData
    },
    eliminarUsuario(user, index) {
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
            this.users.splice(this.ind, 1);

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
    editarUser(item){

      console.log(this.addNewDataSidebar);
      this.addNewDataSidebar = true;
      //this.addNewDataSidebar.id = '1';
      //this.$root('openSidebar');
      /*this.$emit.usuario.id = item.id;
      this.$emit.usuario.id = item.id;
      this.$emit.usuario.name = item.name;
      this.$emit.usuario.roles = item.roles;
      this.$emit.usuario.email = item.email;
      this.$emit.modoEditar = true;*/      
    },
  },
  created() {
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
