<script>
  export default {
    methods: {
      //Carga Tabla 
      $refrescaTabla() {
        const thisIns = this;
        const url = thisIns.ruta
        this.$http.get(url)
          .then(function (response) {
              thisIns.items = response.data.items 
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
      //Actualiza Registros
      $submitActualizar() {
      const thisIns = this;
      const url = thisIns.ruta + this.item.id;
      
      this.$validator.validateAll().then(result =>{
        if (result) {
      
          this.$http.put(url, this.item) 
            .then((res) =>{             
              
              this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
              this.initValues();   
              this.modoEditar  = false;            
              this.popupActive = false;
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
        
              thisIns.$vs.notify({
                title:'Exito',
                text: 'El registro fue actualizado con exito!.',
                color:'success',
                iconPack: 'feather',
                icon:'icon-alert-circle'}) 
                   
            })
            .catch(function (error) {

                thisIns.$vs.notify({
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
    //Agrega Registros
     $submitAgregar() {

      const thisIns = this;
      const url = thisIns.ruta + 'store';

      this.$validator.validateAll().then(result =>{
        if (result) {

         this.$http.post(url, this.item) 
            .then((res) =>{

              this.$vs.loading({ type: 'radius' , scale: 0.6 });
                this.$refrescaTabla();
                this.initValues();
                this.modoEditar = false; 
                this.popupActive=false;
                setTimeout(() => {
                  this.$vs.loading.close()
                }, 500);

              thisIns.$vs.notify({
                title:'Exito',
                text: 'El registro se ha creado con exito!.',
                color:'success',
                iconPack: 'feather',
                icon:'icon-alert-circle'}) 
                
                
                
            })
            .catch(function (error) {

                thisIns.$vs.notify({
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
    //Elimina registo
     $submitEliminar(x, index) {
        this.ite = x;
        this.ind = index;
    
        this.$vs.dialog({
                type: 'confirm',
                color: 'danger',
                title: `Eliminar registro`,
                text: `Esta seguro que desea eliminar el registro seleccionado?`,
                accept: this.$aceptaBorrado,
                cancel: this.$cancelarBorrado,
              
            })
      },
    //Acepta Borrado
    $aceptaBorrado() {
       const thisIns = this;
       const url = thisIns.ruta + this.ite.id;

        this.$http.delete(url)
          .then(()=>{
            this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             this.$vs.notify({
              title:'Exito',
              text: 'El registro fue eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.ite;
              delete this.ind;

          })
          .catch(function (error) {
            this.$vs.notify({
              title:'Error',
              text: error,
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.ite;
              delete this.ind;
          });
     },
      //Cancela Borrado
    $cancelarBorrado() {
          delete this.ite;
          delete this.ind;
    },
    //Acepta el borrado masivo
    $aceptaBorradoMasivo() {
        const thisIns = this;
        const registrosSeleccionados = this.selected;
        const url = thisIns.ruta + 'borrar';

        this.$http.post(url, registrosSeleccionados)
          .then(()=>{
            thisIns.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             thisIns.$vs.notify({
              title:'Exito',
              text: 'Los registros han sido eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})


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
    //Exporta CSV
    $csvExport(arrData) {
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
    },
    //Cierra PopUP
    $cancelarPopUp(){

      this.initValues();
      this.popupActive=false;
      
    },
    //Abre PopUP
    $agregarPopUp(){
  
      this.initValues();
      this.modoEditar = false; 
      this.popupActive=true;
      
    },
    //Acciones masivas
    $accion(seleccion) {
      switch (seleccion) {
        case 1:
            this.aux = this.selected.length;
            if(this.aux > 0){


                this.$vs.dialog({
                  
                  type: 'confirm',
                  color: 'danger',
                  title: `Borrado masivo`,
                  text: `Esta seguro que desea eliminar todos lo registros seleccionados?`,
                  accept: this.$aceptaBorradoMasivo,
              
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
            this.$csvExport(datos);
            break;          
        default:
           return;
    }
    }
      
    },
  };
</script>