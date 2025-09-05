    /**
     * Preview de Nombre - Apellido - Especialidad
     */

    const entityPreview = document.getElementById('entity-preview')
    const titlePreview = document.getElementById('title-preview')
    const startPreview = document.getElementById('start-preview')
    const endPreview = document.getElementById('end-preview')
    const entity = document.formStudy.entity;
    const title = document.formStudy.title_es;
    const start = document.formStudy.start;
    const end = document.formStudy.end;
    //Cuando escriben en name
    entity.addEventListener("keyup", ()=>{
        entityPreview.innerHTML = entity.value;
    });
    //Cuando escriben en title
    title.addEventListener("keyup", ()=>{
        titlePreview.innerHTML = title.value;
    });
    //Cuando escriben en start
    start.addEventListener("keyup", ()=>{
        startPreview.innerHTML = start.value;
    });
    //Cuando escriben en end
    end.addEventListener("keyup", ()=>{
        endPreview.innerHTML = end.value;
    });


//Datemask dd/mm/yyyy para la fecha de inicio y fin
$('#start').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
$('#end').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })