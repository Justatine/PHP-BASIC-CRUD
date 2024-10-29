$(document).ready(function () {
    const url = "http://localhost/api/posts/"

    function getUrlParameter(name) {
        name = name.replace(/[\[\]]/g, "\\$&")
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
          results = regex.exec(window.location.href)
        if (!results) return null
        if (!results[2]) return ''
        return decodeURIComponent(results[2].replace(/\+/g, " "))
    }
    
    dataTable = $('#posts').DataTable({
        ajax: {
          url: url + 'view',
          dataSrc: 'data',
          dataType: 'json',
        },
        columns: [
            {
                data: "id",
                render: function (data) {
                  return `<p class="text-xs font-weight-bold mb-0 text-start">${data}</p>`
                }
            },
            {
                data: "image",
                render: function (data) {
                  return `<img src="../files/${data === null ? 'default.png' : data}" style="height:30px;">`
                }
            },
            {
                data: "title",
                render: function (data) {
                return `<p class="text-xs font-weight-bold mb-0 text-start">${data}</p>`
                }
            },
            {
                data: "content",
                render: function (data) {
                return `<p class="text-xs font-weight-bold mb-0 text-start">${data}</p>`
                }
            },       
            {
                data: null,
                render: function (data) {
                    return `
                        <a href="edit.html?id=${data.id}">
                            <button type="button" class="btn btn-primary" id="editBtn" data-id="${data.id}">Edit</button>
                        </a>
                    `
                }
            },
            {
                data: null,
                render: function (data) {
                    return `<button type="button" class="btn btn-danger delBtn" data-id="${data.id}">Delete</button>`
                }
            },
        ],
        responsive: true,
    })

    $("#addPost").submit(function (e) { 
        e.preventDefault();
        
        var formData=new FormData(this);
        formData.forEach((value, key)=>{
            console.log(key+" "+value)
        })

        $.ajax({
            type: "POST",
            url: url+"add",
            data: formData,
            processData: false,
            contentType: false,  
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert(response.message)
                    setTimeout(() => {
                        window.location.replace('index.html')
                    }, 1000);
                }
            }
        });
    });
    
    $(document).on("click", ".delBtn", function () {
        const id = $(this).data('id')
        console.log(id)
        let formData = new FormData();
        formData.append('id',id)
        
        $.ajax({
            type: "DELETE",
            url: url+"delete",
            data: formData,
            processData: false,
            contentType: false,           
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert(response.message)
                }

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });        
    });

    const id = getUrlParameter('id')
    if (id) {
        $.ajax({
            type: "GET",
            url: url+"view",
            data: { id:id },
            dataType: "json",
            success: function (response) {
                console.log(response.data)
                $("#title").val(response.data.title);
                $("#content").val(response.data.content);
            }
        });
    }   

    $("#editPost").submit(function (e) { 
        e.preventDefault();
        
        var formData=new FormData(this);
        formData.append('id',id)
        var data = {}
        formData.forEach((value, key)=>{
            data[key] = value;
            console.log(key+" "+value)
        })

        $.ajax({
            type: "PUT",
            url: url+"update",
            data: formData,
            processData: false,
            contentType: false,  
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert(response.message)
                    setTimeout(() => {
                        window.location.replace('index.html')
                    }, 1000);
                }
            }
        });
    });
});