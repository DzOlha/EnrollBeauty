
class Notifier {
    static showErrorMessage(message, seconds = 4) {
        alertify.set('notifier', 'position', 'top-center');
        alertify.error(message, seconds);
    }
    static showSuccessMessage(message, seconds = 4) {
        alertify.set('notifier', 'position', 'top-center');
        alertify.success(message, seconds);
    }
}