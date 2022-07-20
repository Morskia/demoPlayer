<script>
    const checkSavedLogin = localStorage.getItem('credentials');
    if(checkSavedLogin){
        localStorage.removeItem('credentials');
    }



    setTimeout(_=>{
        window.location.href= '/tv/login';
    }, 2000)
</script>