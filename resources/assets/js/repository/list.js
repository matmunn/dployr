$(document).ready(function()
{
    $('.new-repository-button').on('click', function () {
        swal({
            title: "New repository",
            html: "Choose your repository type:<br /><br /><a class='btn btn-color-normal' href='{{ action('RepositoryController@new') }}'>Self Hosted</a> <a class='btn btn-color-normal disabled' href='#' title='Coming Soon!'>Github</a><br /><br /><a class='btn btn-color-normal disabled' href='#' title='Coming Soon!'>Bitbucket</a>",
            showCancelButton: true ,
            showConfirmButton: false
        });
    });
});