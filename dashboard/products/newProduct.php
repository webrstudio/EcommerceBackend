<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Agregar nuevo producto
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action='./index.php'>
                <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">Nombre:</label>
                    <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Precio:</label>
                    <input type="number" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Imágen:</label>
                    <input type="file" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Marca:</label>
                    <select class="form-select" aria-label="Default select example">
                        <option required>Seleccione una opción</option>
                        <option value="1">Mesmos Show</option>
                        <option value="2">K Rollo Show</option>
                    </select>
                </div>
                <button class="btn btn-primary">Agregar producto</button>
                </form>
            </div>
            </div>
        </div>
    </div>