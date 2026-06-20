<%@ LANGUAGE="JScript" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filtrar un registro</title>
</head>
<body>
    <%
    // Verificamos si se ha enviado el formulario
    if (Request.Form("Nombre").Count > 0) {
        var Ob_Conn = Server.CreateObject("ADODB.Connection");
        var Ob_RS = Server.CreateObject("ADODB.Recordset");

        // Abrimos la conexión y el recordset
        Ob_Conn.Open("DSN=Alumnos");
        // Nota: adOpenStatic=3, adCmdTable=2
        Ob_RS.Open("Datos_Alumnos", Ob_Conn, 3, 1, 2);

        // Aplicamos el filtro
        var nombreBusqueda = Request.Form("Nombre");
        Ob_RS.Filter = "Nombre = '" + nombreBusqueda + "'";
    %>
        <h3>Estos son las personas encontradas</h3>
        <table border="1">
            <tr>
                <th>DNI</th>
                <th>NOMBRE</th>
                <th>APELLIDOS</th>
                <th>DIRECCION</th>
                <th>TELEFONO</th>
            </tr>
            <% while (!Ob_RS.EOF) { %>
                <tr>
                    <td><%= Ob_RS("DNI").Value %></td>
                    <td><%= Ob_RS("Nombre").Value %></td>
                    <td><%= Ob_RS("Apellidos").Value %></td>
                    <td><%= Ob_RS("Direccion").Value %></td>
                    <td><%= Ob_RS("Telefono").Value %></td>
                </tr>
            <% 
                Ob_RS.MoveNext();
            } 
            %>
        </table>
    <%
        Ob_RS.Close();
        Ob_Conn.Close();
    } else { 
    %>
        <h3>ESCRIBA EL NOMBRE A BUSCAR</h3>
        <br>
        <form method="Post" action="ejem_5.asp">
            NOMBRE: <input name="Nombre" size="10">
            <br><br>
            <input type="Submit" value="Enviar datos">
            <input type="Reset" value="Borrar">
        </form>
    <% } %>
</body>
</html>