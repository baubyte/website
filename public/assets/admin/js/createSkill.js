    /**
     * Preview de Nombre - Apellido - Especialidad
     */
    const namePreview = document.getElementById('name-preview')
    const percentagePreview = document.getElementById('percentage-preview')
    const progress = document.getElementById('progress')
    const name = document.formSkill.name;
    const percentage = document.formSkill.percentage;
    //Cuando escriben en name
    name.addEventListener("keyup", ()=>{
        namePreview.innerHTML = name.value;
    });
    //Cuando escriben en specialty
    percentage.addEventListener("keyup", ()=>{
        percentagePreview.innerHTML = percentage.value + " %" ;
        progress.style.width = percentage.value +"%" ;
    });