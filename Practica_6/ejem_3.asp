<%@ LANGUAGE="JScript" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo Sencillo de BD</title>
</head>
<body>
    <h3>Tabla "Fichas" de la base de datos "EjemploBD" almacenada en el fichero "EjemploBD.mdb"</h3>
    
    <%
    var Ob_Conn = Server.CreateObject("ADODB.Connection");
    Ob_Conn.Open("Alumnos");
    var Ob_RS = Ob_Conn.Execute("SELECT * FROM Datos_Alumnos");
    var Num_Campos = Ob_RS.Fields.Count;
    %>

    <% if (!Ob_RS.EOF) { %>
        <CENTER>
            <TABLE BORDER="1">
                <TR>
                <% for (var Campo = 0; Campo < Num_Campos; Campo++) { %>
                    <TH><%= Ob_RS(Campo).Name %></TH>
                <% } %>
                </TR>

                <% while (!Ob_RS.EOF) { %>
                    <TR>
                    <% for (var Campo = 0; Campo < Num_Campos; Campo++) { %>
                        <TD><%= Ob_RS(Campo).Value %></TD>
                    <% } %>
                    </TR>
                <% 
                    Ob_RS.MoveNext();
                } 
                %>
            </TABLE>
        </CENTER>
    <% 
    } else {
        Response.Write("La tabla no contiene ningún registro");
    }
    
    Ob_RS.Close();
    Ob_Conn.Close();
    %>
</body>
</html>