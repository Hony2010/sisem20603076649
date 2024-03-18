

function readImageURL(input, output) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            //output.attr('href', e.target.result);
            child = output.find("img");
            child.attr('src', e.target.result);
            //return e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function CargarImagen (input,output) {

    readImageURL(input,output);
};

/*********************************************** */

function readImageAsDataURL(input, image) {

    if (input) {
        var reader = new FileReader();

        reader.onload = function (e) {

            image.attr('src', e.target.result);
        }

        reader.readAsDataURL(input);
    }
} 
