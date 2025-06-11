create table usuarios(
    us_id serial primary key,
    us_nombres varchar(100),
    us_apellidos varchar(100),
    us_telefono integer,
    us_direccion varchar(250),
    us_dpi varchar(13),
    us_correo varchar(100),
    us_rol integer,
    us_contrasenia lvarchar(1056),
    us_confirmar_contra lvarchar(1056),
    us_token lvarchar,
    us_fecha_creacion datetime year to minute default current year to minute,
    us_fecha_contrasenia datetime year to minute default current year to minute,
    us_foto lvarchar(2056),
    us_situacion char(1)
);

alter table usuarios add constraint (foreign key(us_rol)
references rol(rol_id) constraint fk_us_rol)



create table rol (
    rol_id serial primary key,
    rol_nombre varchar(75),
    rol_nombre_ct varchar(25),
    rol_situacion char(1)
);



create table aplicacion(
    ap_id serial primary key,
    ap_nombre_lg varchar(150),
    ap_nombre_md varchar(100),
    ap_nombre_ct varchar(50),
    ap_fecha_creacion datetime year to minute default current year to minute,
    ap_situacion char(1)
);



create table permisos(
    per_id serial primary key,
    per_aplicacion integer,
    per_nombre_permiso varchar(250),
    per_clave_permiso varchar(250),
    per_descripcion varchar(250),
    per_fecha datetime year to minute default current year to minute,
    per_situacion char(1)
);

alter table permisos add constraint (foreign key(per_aplicacion)
references aplicacion(ap_id) constraint fk_per_ap)



create table historial_act(
    his_id serial primary key,
    his_usuario integer,
    his_fecha datetime year to minute,
    his_ruta integer,
    his_ejecucion lvarchar,
    his_situacion char(1)
);

alter table historial_act add constraint (foreign key(his_usuario)
references usuarios(us_id) constraint fk_his_us)

alter table historial_act add constraint (foreign key(his_ruta)
references rutas(rut_id) constraint fk_his_rut)



create table rutas(
    rut_id serial primary key,
    rut_aplicacion integer,
    rut_ruta lvarchar,
    rut_descripcion varchar(250),
    rut_situacion char(1)
);

alter table rutas add constraint (foreign key(rut_aplicacion)
references aplicacion(ap_id) constraint fk_rut_ap)



create table marcas_celulares (
    mar_id serial primary key,
    mar_nombre varchar(50),
    mar_descripcion varchar(200),
    mar_fecha_creacion datetime year to minute default current year to minute,
    mar_situacion char(1)
);



create table clientes (
    cli_id serial primary key,
    cli_nombres varchar(100),
    cli_apellidos varchar(100),
    cli_nit varchar(20),
    cli_telefono integer,
    cli_correo varchar(100),
    cli_direccion varchar(250),
    cli_situacion char(1)
);



create table inventario_celulares (
    inv_id serial primary key,
    inv_modelo varchar(100),
    inv_marca integer,
    inv_precio decimal(10,2),
    inv_stock integer,
    inv_estado varchar(20),
    inv_situacion char(1)
);

alter table inventario_celulares add constraint (foreign key(inv_marca)
references marcas_celulares(mar_id) constraint fk_inv_mar);




create table tipos_servicio (
    ts_id serial primary key,
    ts_nombre_servicio varchar(100),
    ts_descripcion varchar(250),
    ts_precio decimal(10,2),
    ts_situacion char(1)
);





create table ventas (
    ven_id serial primary key,
    ven_cliente integer,
    ven_usuario integer,
    ven_fecha_venta datetime year to minute default current year to minute,
    ven_total decimal(10,2),
    ven_observaciones lvarchar,
    ven_situacion char(1)
);

alter table ventas add constraint (foreign key(ven_cliente)
references clientes(cli_id) constraint fk_ven_cli);

alter table ventas add constraint (foreign key(ven_usuario)
references usuarios(us_id) constraint fk_ven_us);




create table detalle_ventas (
    dv_id serial primary key,
    dv_venta integer,
    dv_producto integer,
    dv_descripcion varchar(200),
    dv_cantidad integer,
    dv_precio decimal(10,2),
    dv_precio_total decimal(10,2),
    dv_situacion char(1)
);

alter table detalle_ventas add constraint (foreign key(dv_venta)
references ventas(ven_id) constraint fk_dv_ven);

alter table detalle_ventas add constraint (foreign key(dv_producto)
references inventario_celulares(inv_id) constraint fk_dv_inv);



create table ordenes_reparacion (
    or_id serial primary key,
    or_numero_orden varchar(20),
    or_cliente integer,
    or_usuario_recepcion integer,
    or_fecha_recepcion datetime year to minute default current year to minute,
    or_fecha_entrega datetime year to minute,
    or_marca_dispositivo varchar(50),
    or_modelo_dispositivo varchar(100),
    or_problema_reportado lvarchar,
    or_estado varchar(20),
    or_costo_final decimal(10,2),
    or_situacion char(1)
);

alter table ordenes_reparacion add constraint (foreign key(or_cliente)
references clientes(cli_id) constraint fk_or_cli);

alter table ordenes_reparacion add constraint (foreign key(or_usuario_recepcion)
references usuarios(us_id) constraint fk_or_us_rec);





create table detalle_servicios_orden (
    dso_id serial primary key,
    dso_orden integer,
    dso_tipo_servicio integer,
    dso_descripcion varchar(200),
    dso_precio decimal(10,2),
    dso_fecha_servicio datetime year to minute default current year to minute,
    dso_estado varchar(20) default 'PENDIENTE',
    dso_situacion char(1)
);

alter table detalle_servicios_orden add constraint (foreign key(dso_orden)
references ordenes_reparacion(or_id) constraint fk_dso_or);

alter table detalle_servicios_orden add constraint (foreign key(dso_tipo_servicio)
references tipos_servicio(ts_id) constraint fk_dso_ts);




create table movimientos_inventario (
    mi_id serial primary key,
    mi_producto integer not null,
    mi_tipo_movimiento varchar(20) not null,
    mi_cantidad integer not null,
    mi_motivo varchar(100) not null,
    mi_usuario integer not null,
    mi_fecha_movimiento datetime year to minute default current year to minute,
    mi_referencia_documento varchar(50),
    mi_observaciones varchar(200),
    mi_situacion char(1)
);

alter table movimientos_inventario add constraint (foreign key(mi_producto)
references inventario_celulares(inv_id) constraint fk_mi_inv);

alter table movimientos_inventario add constraint (foreign key(mi_usuario)
references usuarios(us_id) constraint fk_mi_us);

