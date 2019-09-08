<template>
<div>
    <h3>Lista de usuarios</h3>
    <div class="alert alert-danger" v-if="has_error">
        <p>Error, imposible recuperar las informacion en estos momentos, intente mas tarde.</p>
    </div>
    <vs-table
      multiple
      v-model="selected"
      pagination
      max-items="3"
      search
      :data="users"
	  noDataText="No hay datos disponibles">
      <template slot="header">
        <h3>
          Usuarios
        </h3>
      </template>
      <template slot="thead">
        <vs-th sort-key="id">
          ID
        </vs-th>
        <vs-th sort-key="name">
          Nombre
        </vs-th>
        <vs-th sort-key="email">
          Email
        </vs-th>
        <vs-th sort-key="created_at">
          Creado
        </vs-th>
		<vs-th sort-key="created_at">
          Acción
        </vs-th>
      </template>

      <template slot-scope="{data}">
        <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data" >
          <vs-td :data="data[indextr].id">
            {{data[indextr].id}}
          </vs-td>

          <vs-td :data="data[indextr].name">
            {{data[indextr].name}}
          </vs-td>

          <vs-td :data="data[indextr].email">
            {{data[indextr].email}}
          </vs-td>

          <vs-td :data="data[indextr].id">
            {{data[indextr].created_at}}
          </vs-td>

			<vs-td>
		 
			<vs-dropdown vs-trigger-click style="z-index:99999;">
			<vs-button class="btn-drop" type="success-gradient" icon="more_horiz"></vs-button>
				<vs-dropdown-menu>
					<vs-dropdown-item>
					<vs-icon class="" color="success" icon="search"></vs-icon>
					</vs-dropdown-item>
					<vs-dropdown-item>
					<vs-icon class="" color="success" icon="edit"></vs-icon>
					</vs-dropdown-item>
					<vs-dropdown-item divider>
					  <vs-icon class="" color="danger" icon="delete_sweep"></vs-icon>
					</vs-dropdown-item>
				</vs-dropdown-menu>
				</vs-dropdown>
		 	</vs-td>
        </vs-tr>
      </template>
    </vs-table>
</div>
</template>
<script>
  export default {
    data() {
      return {
        has_error: false,
		users: [],
		selected:[],
      }
    },
    mounted() {
      this.getUsers()
    },
    methods: {
      getUsers() {
        this.$http({
          url:'users/users',
          method: 'GET'
        })
            .then((res) => {
              this.users = resc
            }, () => {
              this.has_error = true
            })
      }
    }
  }
</script>