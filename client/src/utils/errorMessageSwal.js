import { getMessage } from '@/utils/message';
import Swal from 'sweetalert2';

export function showError(message, icon = 'error', title = 'Erreur') {
  Swal.fire({
    icon: icon,
    title: title,
    text: getMessage(message),
    confirmButtonColor: '#9155FD', 
    confirmButtonText: `<span style="color: #fff">Ok</span>`
  })
}
