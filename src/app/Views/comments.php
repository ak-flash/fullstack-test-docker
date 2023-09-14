<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="p-lg-4">
	<h1 class="display-6 mb-4">
		Комментарии
	</h1>

    <div id="notification" class="d-none alert alert-success mb-4 p-2"></div>

    <div>
        <div class="d-flex mb-4">
            <div class="px-4">
                Cортировка по:
            </div>
            <select id="sortBy" class="form-control" onchange="setSortOrder()">
                <option selected value="id">Id</option>
                <option value="date">дате добавления</option>
            </select>
            <div class="px-4">
                Направление сортировки:
            </div>
            <select id="orderBy" class="form-control" onchange="setSortOrder()">
                <option value="ASC">по возрастанию</option>
                <option selected value="DESC">по убыванию</option>
            </select>
        </div>

        <template id="commentsTemplate">
            <div class="border rounded p-4 mb-4 d-flex align-middle">
                <div class="pr-2">
                    ID: {{id}}
                </div>
                <div class="w-100">
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div>
                           {{name}} ({{email}})
                        </div>
                        <div class="d-flex">
                            <span class="pr-4">
                               {{date}}
                            </span>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteComment({{id}})">Удалить</button>
                        </div>
                    </div>
                    <p class="mt-3 mb-4 pb-2">
                        {{message}}
                    </p>
                </div>

            </div>
        </template>

        <div id="commentsDiv"></div>

        <nav id="pagination" class="d-none align-middle mx-auto">
            <div class="d-flex gap-4 px-4">
                <span class="pr-2">
                   Страница:
                </span>
                <div id="currentPage" class="pr-2 font-weight-bold"></div>
                <span class="pr-2">
                    из
                </span>
                <div id="totalPages"></div>
            </div>


            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" id="previosPage"  onclick="getComments(currentPage - 1)" href="#" aria-label="Назад">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Назад</span>
                    </a>
                </li>

                <template id="paginationTemplate">
                    <li id="page-{{page}}" class="page-item"><a class="page-link" href="#" onclick="getComments({{page}})">{{page}}</a></li>
                </template>

                <div id="paginationDiv" class="d-flex"></div>

                <li class="page-item">
                    <a class="page-link" id="nextPage" onclick="getComments(currentPage + 1)" href="#" aria-label="Вперед">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Вперед</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="card-footer p-4 border-0">
            <h3 class="mb-2 border-bottom pb-4">
                Добавить комментарий
            </h3>


            <form id="addCommentForm">
                <div class="form-group">
                    <label for="inputName">Имя</label>
                    <input id="inputName" name="name" type="text" class="form-control" placeholder="Введите ваше имя">
                    <div id="validationError-name" class="validationError" role="alert"></div>
                </div>
                <div class="d-flex input-group input-group-lg">
                    <div class="form-group mr-4">
                        <label for="inputEmail">Email</label>
                        <input id="inputEmail" name="email" type="email" class="form-control" placeholder="Введите email">
                        <div id="validationError-email" class="validationError" role="alert"></div>
                    </div>

                    <div class="form-group">
                        <label for="inputDate">Дата</label>
                        <input id="inputDate" name="date" type="date" class="form-control" placeholder="Введите дату">
                        <div id="validationError-date" class="validationError" role="alert"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="textareaMessage">Сообщение</label>
                    <textarea id="textareaMessage" name="message" class="form-control" rows="4"></textarea>
                    <div id="validationError-message" class="validationError" role="alert"></div>
                </div>

                <button id="btnAddComment" type="submit" class="btn btn-primary btn-sm">Отправить</button>
            </form>
        </div>
    </div>


</div>

<script>
    let apiUrl = "http://localhost/api/v1"
    let currentPage = 1
    let totalPages = 1

    getComments()

    function getComments(page = 1, sortBy = 'id', orderBy = 'DESC') {

        $.get(apiUrl+"/comments?page="+page+"&sortBy="+sortBy+"&orderBy="+orderBy, function(data) {

            currentPage = data.currentPage
            totalPages = data.totalPages
            makePagination(currentPage, totalPages)

            fillTemplate(data.comments, 'commentsTemplate', 'commentsDiv')
        })
            .done(function() {

            })
            .fail(function(error) {
                showNotification(error.message)
            });
    }

    function fillTemplate(data, templateId, targetId) {
        let template = $('#'+templateId).html();
        $('#'+targetId).empty();

        $.each(data, function (index, obj) {
            let filled = template

            Object.keys(obj).forEach(key => {
                filled = filled.replaceAll('{{'+key+'}}', obj[key]);
            });

            $('#'+targetId).append(filled);
        });
    }

    function makePagination(currentPage, totalPages) {
        $('#currentPage').html(currentPage)
        $('#totalPages').html(totalPages)

        let nextPage = $('#nextPage')
        let previosPage = $('#previosPage')

        if(currentPage !== 1 && currentPage !== totalPages) {
            nextPage.removeClass("d-none")
            previosPage.removeClass("d-none")
        }

        // Hide previos page link
        if(currentPage === 1) {
            previosPage.addClass("d-none")
        }

        // Hide next page link
        if(currentPage === totalPages) {
            nextPage.addClass("d-none")
        }

        // Generate page links
        let pages = {}

        for (let i = 1; i <= totalPages; i++) {
            pages[i] = {
                'page': i,
            }
        }

        fillTemplate(pages, 'paginationTemplate', 'paginationDiv')

        $('#page-'+currentPage).addClass("active")
        $('#pagination').removeClass("d-none").addClass("d-flex")
    }

    $("#addCommentForm").submit(function (event) {
        event.preventDefault();

        let comment = {
            name: $("#inputName").val(),
            email: $("#inputEmail").val(),
            date: $("#inputDate").val(),
            message: $("#textareaMessage").val(),
        }

        $('.validationError').addClass("d-none")

        $("#btnAddComment").prop("disabled", true);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: apiUrl+"/comments",
            data: JSON.stringify(comment),
            dataType: 'json',
            contentType: 'application/json',
            processData: false,
            success: function (result) {
                if(result.error === null) {
                    $('html, body').scrollTop($("#notification").offset().top);
                    getComments()
                    showNotification(result.messages.success)
                    $('#addCommentForm')[0].reset();
                }

                $("#btnAddComment").prop("disabled", false);
            },

            error: function (errors) {
                showValidationError(errors)
                $("#btnAddComment").prop("disabled", false);
            }
        });
    });

    function showValidationError(errors) {
        const e = JSON.parse(errors.responseText)

        $.each(e.messages, function (index, obj) {
            let element = $('#validationError-'+index)
            element.html(obj);
            element.addClass("alert alert-danger mt-2").removeClass("d-none")
        })
    }

    function deleteComment(commentId) {
        $.ajax({
            url: apiUrl+'/comments/'+commentId,
            type: 'DELETE',
            success: function(result) {

                if(result.error === null) {
                    getComments(currentPage)
                    showNotification(result.messages.success)
                }

            }
        });
    }

    function showNotification(text) {
        let notificationDiv = $('#notification')

        notificationDiv.text(text)

        notificationDiv.removeClass("d-none")
        notificationDiv.fadeIn('fast');

        setTimeout(function() {
            notificationDiv.fadeOut('fast');
        }, 5000);

    }

    function setSortOrder() {
        let sortBy = $('#sortBy').val()
        let orderBy = $('#orderBy').val()
        getComments(1, sortBy, orderBy)
    }
</script>


<?= $this->endSection() ?>
