const eliminarButtons=document.querySelectorAll(".eliminar");function confirmarEliminarProyecto(t){Swal.fire({title:"¿Deseas eliminar este proyecto?",showCancelButton:!0,confirmButtonText:"Sí",cancelButtonText:"Cancelar"}).then(o=>{o.isConfirmed&&eliminarProyecto(t)})}async function eliminarProyecto(t){const o=new FormData;o.append("id",t);try{const t="http://localhost:5000/eliminar-proyecto",e=await fetch(t,{method:"POST",body:o});(await e.json()).eliminado&&(Swal.fire({position:"center",icon:"success",title:"Eliminado Correctamente",showConfirmButton:!1,timer:2e3}),setTimeout(()=>{window.location.href="http://localhost:5000/dashboard"},2e3))}catch(t){console.log(t)}}eliminarButtons.forEach(t=>{t.addEventListener("click",()=>{confirmarEliminarProyecto(t.id)})});