$(document).ready(function () { // ~= document.addEventListener('DOMContentLoaded', () {

  $('.increment').click(function () {

    // <button class="decrement" data-id="{{ capture.id }}">-1</button>
    let captureId = $(this).data('id');
    $.ajax({
      url: '/shasse/' + captureId + '/increment',
      type: 'POST',
      success: function (data) {

        //   <p class="compteur" id="compteur-{{ capture.id }}">{{capture.nbRencontres}}</p>
        $('#compteur-' + captureId).val(data.nbRencontres);
      }
    });
  });

  $('.decrement').click(function () {
    let captureId = $(this).data('id');
    $.ajax({
      url: '/shasse/' + captureId + '/decrement',
      type: 'POST',
      success: function (data) {
        $('#compteur-' + captureId).val(data.nbRencontres);
      }
    });
  });

  $('.increment10').click(function () {
    let captureId = $(this).data('id');
    $.ajax({
      url: '/shasse/' + captureId + '/increment10',
      type: 'POST',
      success: function (data) {
        $('#compteur-' + captureId).val(data.nbRencontres);
      }
    });
  });

  $('.decrement10').click(function () {
    let captureId = $(this).data('id');
    $.ajax({
      url: '/shasse/' + captureId + '/decrement10',
      type: 'POST',
      success: function (data) {
        $('#compteur-' + captureId).val(data.nbRencontres);
      }
    });
  });

  $('.compteur-input').change(function () {
    let captureId = $(this).attr('id').split('-')[1]; // Extraction de l'ID à partir de l'ID de l'input
    let newValue = $(this).val(); // Nouvelle valeur entrée par l'utilisateur

    $.ajax({
      url: '/shasse/' + captureId + '/updateCounter',
      type: 'POST',
      data: {
        nbRencontres: newValue
      },
      success: function (data) {
        $('#compteur-' + captureId).val(data.nbRencontres);
      }
    });
  });
});