<script>
  export default {
    methods: {
      $can(permissionName) {
        //console.log(Permissions);
        return Permissions.indexOf(permissionName) !== -1;
      },    
    },
  };
</script>