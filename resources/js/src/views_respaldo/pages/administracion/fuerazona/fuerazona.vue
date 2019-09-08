<template>
  <div id="data-list-list-view" class="data-list-container">

  

    <vs-table ref="table" multiple v-model="selected" pagination :max-items="itemsPerPage" search :data="items">

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
              <vx-tooltip color="primary" text="Agregar fuera de zona">
              <span class="ml-2 text-base text-primary">Agregar Fuera de zona</span>
              </vx-tooltip>
          </div>
        </div>

         <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR FUERA DE ZONA' : 'ACTUALIZAR FUERA DE ZONA')" 
         :active.sync="popupActive"  @hidden="cancelarPopUp">
            <div class="p-5">
              <!-- EMPRESA -->
                <vs-select v-model="item.empresa_id" label="Empresa" name="empresa" class="mt-5 w-full" v-validate="'required'" >
                <vs-select-item :key="item.id" :value="item.id" :text="item.razon_social" v-for="item in empresa_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('empresa')">{{ errors.first('empresa') }}</span>
              <!-- TIPO -->
              <vs-select v-model="item.tipo" label="Tipo" name="tipo" class="mt-5 w-full" v-validate="'required'" >
                <vs-select-item :key="item.id" :value="item.id" :text="item.descripcion" v-for="item in tipo_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('tipo')">{{ errors.first('tipo') }}</span>  
              <!-- COMUNA -->
              <vs-input v-validate="'required'" label-placeholder="Comuna" name="comuna" v-model="item.comuna" 
              :danger="(errors.first('rut') ? true : false)" val-icon-danger="clear" class="mt-5 w-full" />
              <span class="text-danger text-sm" v-show="errors.has('comuna')">{{ errors.first('comuna') }}</span>
              <!-- DISTANCIA -->
              <vs-input v-validate="'required|numeric'" label-placeholder="Distancia" name="distancia" v-model="item.distancia" 
              :danger="(errors.first('distancia') ? true : false)" val-icon-danger="clear" class="mt-5 w-full" />
              <span class="text-danger text-sm" v-show="errors.has('distancia')">{{ errors.first('distancia') }}</span>
           

            <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="submitFormActualizar" >ACTUALIZAR FUERA DE ZONA</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="submitForm">AGREGAR FUERA DE ZONA</vs-button>
              
           
              <vs-button type="border" color="danger" ref="btncancelar" @click.prevent="cancelarPopUp">CANCELAR</vs-button>
            </div>
            </div>
        </vs-popup>
        <!-- POP UP -->

        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ items.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : items.length }} of {{ items.length }}</span>
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
        <vs-th sort-key="items-comuna">Comuna</vs-th>
        <vs-th sort-key="items-tipo">Tipo</vs-th>
        <vs-th sort-key="items-distancia">Distancia</vs-th>
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
          <tbody>
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="items-id font-medium">{{ tr.id }}</p>
              </vs-td>

              <vs-td>
                <p class="items-comuna">{{ tr.comuna }}</p>
              </vs-td>
               <vs-td>
                <p class="items-tipo">{{ getTipo(tr.tipo) }}</p>
              </vs-td>
                 <vs-td>
                <p class="items-distancia">{{ tr.distancia  }}</p>
              </vs-td>
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
      items: [],
      itemsPerPage: 4,
      isMounted: false,
      fue : "",
      ind : "",
      popupActive: false,
      empresa_choices: [],
      tipo_choices: [
        { descripcion: "Fuera de zona 1", id: 1 },
        { descripcion: "Fuera de zona 2", id: 2 },
        { descripcion: "Fuera de zona 3", id: 3 },
       
      ],
      item : {},
      modoEditar: false,      
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
            const datos = this.items;
            this.csvExport(datos);
            break;          
        default:
           return;
    }
    },
    getTipo(tipo) {
      if(tipo == 1) return "Fuera de zona 1"
      if(tipo == 2) return "Fuera de zona 2"
      if(tipo == 3) return "Fuera de zona 3"
      return "ninguno"
    },
    acceptAlertMasivo() {
        const thisDelM = this;
        const registrosSeleccionados = this.selected;

        this.$http.post(`/fuerazonas/fuerazonas/borrar`, registrosSeleccionados)
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
         //Carga Excepciones
        const thisIns = this;
        this.$http.get('fuerazonas/fuerazonas')
          .then(function (response) {
              thisIns.items = response.data.fuerazonas //thisIns.formatData(response.data.users) formatear data
          })
          .catch(function (error) {
            thisIns.$vs.notify({
              title:'Error',
              text: error,
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})
          });
          

      },refrescaOtrosDatos() {
        //Carga Empresa
        const thisIns = this;
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
    eliminar(fue, index) {
        this.fue = fue;
        this.ind = index;
    
        this.$vs.dialog({
                type: 'confirm',
                color: 'danger',
                title: `Eliminar fuera de zona`,
                text: `Esta seguro que desea eliminar la fuera zona: ${fue.comuna} ?`,
                accept: this.acceptAlert,
                cancel: this.cancelAlert,
              
            })
        },
    cancelAlert() {
          delete this.fue;
          delete this.ind;
    },
    acceptAlert() {
        this.$http.delete(`/fuerazonas/fuerazonas/${this.fue.id}`)
          .then(()=>{
            this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.refrescaTabla();
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             this.$vs.notify({
              title:'Exito',
              text: 'La fuera de zona ha sido eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.fue;
              delete this.ind;

          })
          .catch(function (error) {
            this.$vs.notify({
              title:'Error',
              text: error,
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.fue;
              delete this.ind;
          });
     },
    editar(item){      
      this.initValues();
      this.modoEditar = true; 
      this.item.id = item.id;
      this.item.empresa_id = item.empresa_id;
      this.item.comuna = item.comuna;
      this.item.distancia = item.distancia;
      this.item.tipo = item.tipo;
      this.popupActive = true;
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

      this.item = {};
      this.item.empresa_id = '';
      this.item.tipo = '';
      this.errors.clear();

    },

    submitFormActualizar() {
      const thisInsAdd = this;
      this.$validator.validateAll().then(result =>{
        if (result) {
          const fuerazonaAct = this.item;
        this.$http.put('fuerazonas/fuerazonas/' + this.item.id, fuerazonaAct) 
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
                text: 'El fuera de zona se ha actualizado con exito!.',
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

          const fuerazonaNueva = this.item;
         this.$http.post('fuerazonas/fuerazonas/store', fuerazonaNueva) 
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
                text: 'El fuera de zona se ha creado con exito!.',
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
