
class Notifier {
    static showErrorMessage(message, seconds) {
        alertify.set('notifier', 'position', 'top-center');
        alertify.error(message, seconds);
    }

    static showSuccessMessage(message, seconds) {
        alertify.set('notifier', 'position', 'top-center');
        alertify.success(message, seconds);
    }
}

export default Notifier;
