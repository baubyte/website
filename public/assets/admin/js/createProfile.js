    /**
     * Custom File INPUTS
     */
     $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    
    /**
     * Carga Preview Imagen
     */
    const avatar = document.getElementById("avatar");
    const preview = document.getElementById("preview");
    // Escuchar cuando cambie
    avatar.addEventListener("change", () => {
        // Los archivos seleccionados, pueden ser muchos o uno
        const archivos = avatar.files;
        // Si no hay archivos salimos de la función y quitamos la imagen
        if (!archivos || !archivos.length) {
            preview.src = "";
            return;
        }
        // Ahora tomamos el primer archivo, el cual vamos a previsualizar
        const imagen = archivos[0];
        // Lo convertimos a un objeto de tipo objectURL
        const objectURL = URL.createObjectURL(imagen);
        // Y a la fuente de la imagen le ponemos el objectURL
        preview.src = objectURL;
    });
    /**
     * Preview de Nombre - Apellido - Especialidad
     */
    const namePreview = document.getElementById('name-preview')
    const surnamePreview = document.getElementById('surname-preview')
    const specialtyPreview = document.getElementById('specialty-preview')
    const name = document.formProfile.name;
    const surname = document.formProfile.surname;
    const specialtyEs = document.formProfile.specialty_es;
    //Cuando escriben en name
    name.addEventListener("keyup", ()=>{
        namePreview.innerHTML = name.value;
    });
    //Cuando escriben en surname
    surname.addEventListener("keyup", ()=>{
        surnamePreview.innerHTML = surname.value;
    });
    //Cuando escriben en specialty
    specialtyEs.addEventListener("keyup", ()=>{
        specialtyPreview.innerHTML = specialtyEs.value;
    });
    /**
     * Carga url github
     */
     const githubHref = document.getElementById("github_href");
     const githubUrl = document.getElementById("github_url");
     // Escuchar cuando cambie
     githubUrl.addEventListener("keyup", () => {
         // Cargamos el valor del input en el href
         githubHref.href = githubUrl.value;
     });
    /**
    * Carga url linkedin
    */
     const linkedinHref = document.getElementById("linkedin_href");
     const linkedinUrl = document.getElementById("linkedin_url");
     // Escuchar cuando cambie
     linkedinUrl.addEventListener("keyup", () => {
         // Cargamos el valor del input en el href
         linkedinHref.href = linkedinUrl.value;
     });
    /**
    * Carga url Instagram
    */
     const instagramHref = document.getElementById("instagram_href");
     const instagramUrl = document.getElementById("instagram_url");
     // Escuchar cuando cambie
     instagramUrl.addEventListener("keyup", () => {
         // Cargamos el valor del input en el href
         instagramHref.href = instagramUrl.value;
     });