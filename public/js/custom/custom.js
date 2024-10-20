console.log("custom.js loaded");

// Funzioni per la gestione dei checkbox di selezione
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="messages[]"]');
    const invertSelectionBtn = document.getElementById('invert-selection');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('click', function () {
            const isChecked = selectAllCheckbox.checked;
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });
    }

    if (invertSelectionBtn) {
        invertSelectionBtn.addEventListener('click', function (e) {
            e.preventDefault();
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = !checkbox.checked;
            });
            updateSelectAllState();
        });
    }

    function updateSelectAllState() {
        if (selectAllCheckbox) {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
        }
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateSelectAllState);
    });
});

// Funzioni per le azioni sui messaggi
function bulkAction(actionUrl) {
    const selectedMessages = Array.from(document.querySelectorAll('input[name="messages[]"]:checked')).map(
        checkbox => checkbox.value
    );

    if (selectedMessages.length === 0) {
        alert('Please select at least one message.');
        return;
    }

    const form = document.getElementById('bulk-actions-form');
    if (form) {
        form.action = actionUrl;
        form.submit();
    } else {
        console.error('Bulk actions form not found');
    }
}

function deleteMessage(id) {
    const form = document.getElementById('delete-form');
    if (form) {
        form.action = `/messages/${id}/delete`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    } else {
        console.error('Delete form not found');
    }
}

function archiveMessage(id) {
    const form = document.getElementById('bulk-actions-form');
    if (form) {
        form.action = `/messages/${id}/archive`;
        form.submit();
    } else {
        console.error('Bulk actions form not found');
    }
}

function restoreMessage(id) {
    const form = document.getElementById('bulk-actions-form');
    if (form) {
        form.action = `/messages/${id}/restore`;
        form.submit();
    } else {
        console.error('Bulk actions form not found');
    }
}

function toggleLumina(luminableId, luminableType, action) {
    const url = `/sonet/lumina/${action}`; // Aggiungi o rimuovi in base all'azione
    const data = {
        luminable_id: luminableId,
        luminable_type: luminableType,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Aggiorna il conteggio delle Lumina
                const countElement = document.getElementById(`lumina-count-${luminableId}`) ||
                    document.getElementById(`lumina-count-comment-${luminableId}`);

                // Modifica il conteggio in base all'azione
                if (action === 'add') {
                    countElement.textContent = parseInt(countElement.textContent) + 1;
                } else if (action === 'remove') {
                    countElement.textContent = parseInt(countElement.textContent) - 1;
                }

                // Cambia lo stato del pulsante o dell'icona
                const toggleButton = document.getElementById(`toggle-lumina-${luminableId}`);
                if (action === 'add') {
                    toggleButton.setAttribute('data-action', 'remove');
                    toggleButton.textContent = 'Rimuovi Lumina';
                } else {
                    toggleButton.setAttribute('data-action', 'add');
                    toggleButton.textContent = 'Aggiungi Lumina';
                }
            }
        })
        .catch(error => console.error('Errore:', error));
}

document.getElementById('media').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('mediaPreview');
    previewContainer.innerHTML = ''; // Resetta l'anteprima

    if (file) {
        const fileReader = new FileReader();
        fileReader.onload = function (e) {
            if (file.type.startsWith('image/')) {
                // Mostra l'anteprima dell'immagine
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-fluid', 'mt-2');
                previewContainer.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                // Mostra l'anteprima del video
                const video = document.createElement('video');
                video.src = e.target.result;
                video.controls = true;
                video.classList.add('img-fluid', 'mt-2');
                previewContainer.appendChild(video);
            }
        };
        fileReader.readAsDataURL(file);
    }
});

// Funzioni per la gestione del modal di azione
document.addEventListener('DOMContentLoaded', function () {
    $('#actionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Bottone che ha attivato il modal
        var action = button.data('action'); // Tipo di azione: post, comment, reply
        var sonetId = button.data('sonet-id') || null;
        var commentId = button.data('comment-id') || null;
        var sonetContent = button.data('sonet-content') || '';
        var commentContent = button.data('comment-content') || '';
        var author = button.data('author') || '';

        var modalTitle = '';
        var formAction = '';
        var visibilityField = $('#visibilityField');
        var publishAtField = $('#publishAtField');
        var mediaField = $('#mediaField');
        var contextContent = $('#contextContent');
        var contextText = $('#contextText');

        // Reset dei campi nascosti e della visibilità
        $('#parentId').val(null);
        $('#sonetPostId').val(null);
        $('textarea[name="content"]').val('');
        visibilityField.hide();
        publishAtField.hide();
        mediaField.hide();
        contextContent.hide();

        // Configura il modal in base all'azione
        if (action === 'post') {
            modalTitle = 'Pubblica un Sonet';
            formAction = routes.storeSonet; // Rotta per creare un Sonet
            visibilityField.show();
            publishAtField.show();
            mediaField.show();
            $('#submitButton').text('Pubblica il sonet');
        } else if (action === 'comment') {
            modalTitle = 'Commenta il sonet di ' + author;
            formAction = routes.commentStore; // Rotta per creare un commento
            $('#sonetPostId').val(sonetId); // ID del Sonet a cui si sta commentando
            $('#submitButton').text('Invia commento');
            contextText.text('Sonet: ' + sonetContent);
            contextContent.show();
        } else if (action === 'reply') {
            modalTitle = 'Rispondi al commento di ' + author;
            formAction = routes.commentStore; // Rotta per rispondere a un commento
            $('#parentId').val(commentId); // ID del commento a cui si risponde
            $('#sonetPostId').val(sonetId); // ID del Sonet correlato
            $('#submitButton').text('Invia risposta');
            contextText.text('Commento: ' + commentContent);
            contextContent.show();
        } else {
            // Azione sconosciuta, chiudi il modal
            $('#actionModal').modal('hide');
            console.error('Azione non riconosciuta: ' + action);
            return;
        }

        // Imposta l'azione del form e il titolo del modal
        $('#actionForm').attr('action', formAction);
        $('#actionModalLabel').text(modalTitle);
    });

    // Reset campi quando il modal viene chiuso
    $('#actionModal').on('hidden.bs.modal', function () {
        $('#actionForm').trigger('reset');
        $('#actionForm').attr('action', '');
        $('#parentId').val(null);
        $('#sonetPostId').val(null);
        $('#actionModalLabel').text('');
        $('#submitButton').text('');
        $('#contextContent').hide();
    });
});

// Funzioni per il caricamento dinamico dei post e dei commenti
let page = 1;
let loading = false;

function loadSonets() {
    if (loading) return;
    loading = true;

    $('#loading').show();

    $.ajax({
        url: routes.timeline,
        type: 'GET',
        data: { page: page },
        success: function (data) {
            if (data.trim() === '') {
                $('#loading').hide();
                return;
            }
            $('#sonet-list').append(data);
            page++;
            loading = false;
            $('#loading').hide();
        },
        error: function () {
            $('#loading').hide();
            loading = false;
            alert('Errore durante il caricamento dei sonet.');
        }
    });
}

$(document).ready(function () {
    loadSonets();

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadSonets();
        }
    });
});

function loadMorePosts(page) {
    var postList = $('#sonet-list');
    var loadingSpinner = $('#loading-posts');

    loadingSpinner.show();

    $.ajax({
        url: '/sonet/posts/load-more',
        method: 'GET',
        data: { page: page },
        success: function (response) {
            postList.append(response);
            loadingSpinner.hide();

            if (response.trim() === '' || $(response).find('.card').length < 24) {
                $('#load-more-posts').hide();
            } else {
                var nextPage = page + 1;
                $('#load-more-posts').attr('onclick', 'loadMorePosts(' + nextPage + ')');
            }
        },
        error: function (xhr) {
            loadingSpinner.hide();
            alert('Errore durante il caricamento dei post.');
        }
    });
}

function loadMoreComments(sonetId, offset) {
    var commentList = $('#comment-list-' + sonetId);
    var loadingSpinner = $('#loading-comments-' + sonetId);

    loadingSpinner.show();

    $.ajax({
        url: '/sonet/' + sonetId + '/comments/load-more',
        method: 'GET',
        data: { offset: offset },
        success: function (response) {
            if (response.trim() === '') {
                $('#load-more-' + sonetId).hide();
                loadingSpinner.hide();
                return;
            }
            commentList.append(response);
            loadingSpinner.hide();

            var newOffset = offset + 24;
            $('#load-more-' + sonetId).attr('onclick', 'loadMoreComments(' + sonetId + ', ' + newOffset + ')');
        },
        error: function () {
            loadingSpinner.hide();
            alert('Errore durante il caricamento dei commenti.');
        }
    });
}

// Funzioni per la gestione delle notifiche
function checkForNewNotifications() {
    fetch('/notifications/check-notifications', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.hasNotifications) {
                loadNotifications();
            }
        })
        .catch(error => console.error('Error:', error));
}

function loadNotifications() {
    fetch('/messages/notifications', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('notification-list').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');
    if (badge) {
        badge.textContent = count > 0 ? count : '';
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

// Funzioni per la gestione del form di risposta e inoltro
function validateReplyForm() {
    const replyMessage = document.querySelector('textarea[name="reply_message"]').value.trim();
    if (replyMessage === '') {
        alert('Il messaggio di risposta non può essere vuoto.');
        return false;
    }
    return true;
}

function validateForwardForm() {
    const recipient = document.querySelector('select[name="recipient_id"]').value;
    if (!recipient) {
        alert('Seleziona un destinatario per inoltrare il messaggio.');
        return false;
    }
    return true;
}

// Funzioni periodiche e temporizzate
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.classList.remove('show');
    });
}, 12000);

setInterval(checkForNewNotifications, 12000);

setInterval(() => {
    fetch('/update-ids')
        .then(response => response.json())
        .then(data => {
            console.log('IDS updated successfully:', data);
        })
        .catch(error => console.error('Error updating IDS:', error));
}, 60000);
