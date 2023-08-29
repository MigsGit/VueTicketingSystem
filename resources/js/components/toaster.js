/*
    ! This is where toastr notification code is constructed and will be imported to other frontend
*/
import toastr from "toastr";
import 'toastr/build/toastr.min.css';
// import 'toastr/build/toastr.min.js';

export function useToastr(){
    toastr.options.progressBar = true;
    toastr.options.timeOut  = 1500;
    return toastr;

}