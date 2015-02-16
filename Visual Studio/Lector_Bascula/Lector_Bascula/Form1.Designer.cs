namespace Lector_Bascula
{
    partial class Form1
    {
        /// <summary>
        /// Variable del diseñador requerida.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Limpiar los recursos que se estén utilizando.
        /// </summary>
        /// <param name="disposing">true si los recursos administrados se deben eliminar; false en caso contrario, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Código generado por el Diseñador de Windows Forms

        /// <summary>
        /// Método necesario para admitir el Diseñador. No se puede modificar
        /// el contenido del método con el editor de código.
        /// </summary>
        private void InitializeComponent()
        {
            this.pictureBoxBascula = new System.Windows.Forms.PictureBox();
            this.label1 = new System.Windows.Forms.Label();
            this.menuStrip1 = new System.Windows.Forms.MenuStrip();
            this.archivoToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.conectarToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.desconectarToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.salirToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.ayudaToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.toolStripMenuItem2 = new System.Windows.Forms.ToolStripMenuItem();
            this.acercaDeBASCULACOPEOToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.label2 = new System.Windows.Forms.Label();
            this.pictureBoxLector = new System.Windows.Forms.PictureBox();
            this.label4 = new System.Windows.Forms.Label();
            this.pictureBoxListoLeer = new System.Windows.Forms.PictureBox();
            this.label5 = new System.Windows.Forms.Label();
            this.label6 = new System.Windows.Forms.Label();
            this.label7 = new System.Windows.Forms.Label();
            this.dgvDatos = new System.Windows.Forms.DataGridView();
            this.EPC = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.PESO = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.statusStrip1 = new System.Windows.Forms.StatusStrip();
            this.labelAvisos = new System.Windows.Forms.Label();
            this.buttonStart = new System.Windows.Forms.Button();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxBascula)).BeginInit();
            this.menuStrip1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLector)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxListoLeer)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.dgvDatos)).BeginInit();
            this.SuspendLayout();
            // 
            // pictureBoxBascula
            // 
            this.pictureBoxBascula.BackColor = System.Drawing.Color.Red;
            this.pictureBoxBascula.Location = new System.Drawing.Point(28, 102);
            this.pictureBoxBascula.Name = "pictureBoxBascula";
            this.pictureBoxBascula.Size = new System.Drawing.Size(20, 20);
            this.pictureBoxBascula.TabIndex = 0;
            this.pictureBoxBascula.TabStop = false;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(84, 145);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(50, 13);
            this.label1.TabIndex = 1;
            this.label1.Text = "LECTOR";
            // 
            // menuStrip1
            // 
            this.menuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.archivoToolStripMenuItem,
            this.ayudaToolStripMenuItem});
            this.menuStrip1.Location = new System.Drawing.Point(0, 0);
            this.menuStrip1.Name = "menuStrip1";
            this.menuStrip1.Size = new System.Drawing.Size(584, 24);
            this.menuStrip1.TabIndex = 2;
            this.menuStrip1.Text = "menuStrip1";
            // 
            // archivoToolStripMenuItem
            // 
            this.archivoToolStripMenuItem.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.conectarToolStripMenuItem,
            this.desconectarToolStripMenuItem,
            this.salirToolStripMenuItem});
            this.archivoToolStripMenuItem.Name = "archivoToolStripMenuItem";
            this.archivoToolStripMenuItem.Size = new System.Drawing.Size(60, 20);
            this.archivoToolStripMenuItem.Text = "&Archivo";
            // 
            // conectarToolStripMenuItem
            // 
            this.conectarToolStripMenuItem.Name = "conectarToolStripMenuItem";
            this.conectarToolStripMenuItem.Size = new System.Drawing.Size(139, 22);
            this.conectarToolStripMenuItem.Text = "&Conectar";
            this.conectarToolStripMenuItem.ToolTipText = "Realizar la conexión con los dispositivos";
            this.conectarToolStripMenuItem.Click += new System.EventHandler(this.conectarToolStripMenuItem_Click);
            // 
            // desconectarToolStripMenuItem
            // 
            this.desconectarToolStripMenuItem.Name = "desconectarToolStripMenuItem";
            this.desconectarToolStripMenuItem.Size = new System.Drawing.Size(139, 22);
            this.desconectarToolStripMenuItem.Text = "&Desconectar";
            this.desconectarToolStripMenuItem.ToolTipText = "Realizar la desconexión con los dispositivos";
            this.desconectarToolStripMenuItem.Click += new System.EventHandler(this.desconectarToolStripMenuItem_Click);
            // 
            // salirToolStripMenuItem
            // 
            this.salirToolStripMenuItem.Name = "salirToolStripMenuItem";
            this.salirToolStripMenuItem.Size = new System.Drawing.Size(139, 22);
            this.salirToolStripMenuItem.Text = "&Salir";
            this.salirToolStripMenuItem.ToolTipText = "Salir";
            // 
            // ayudaToolStripMenuItem
            // 
            this.ayudaToolStripMenuItem.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.toolStripMenuItem2,
            this.acercaDeBASCULACOPEOToolStripMenuItem});
            this.ayudaToolStripMenuItem.Name = "ayudaToolStripMenuItem";
            this.ayudaToolStripMenuItem.Size = new System.Drawing.Size(53, 20);
            this.ayudaToolStripMenuItem.Text = "A&yuda";
            // 
            // toolStripMenuItem2
            // 
            this.toolStripMenuItem2.Name = "toolStripMenuItem2";
            this.toolStripMenuItem2.Size = new System.Drawing.Size(224, 22);
            this.toolStripMenuItem2.Text = "&Ver la Ayuda";
            this.toolStripMenuItem2.ToolTipText = "Visualizar la ayuda del programa";
            // 
            // acercaDeBASCULACOPEOToolStripMenuItem
            // 
            this.acercaDeBASCULACOPEOToolStripMenuItem.Name = "acercaDeBASCULACOPEOToolStripMenuItem";
            this.acercaDeBASCULACOPEOToolStripMenuItem.Size = new System.Drawing.Size(224, 22);
            this.acercaDeBASCULACOPEOToolStripMenuItem.Text = "&Acerca de BASCULA-COPEO";
            this.acercaDeBASCULACOPEOToolStripMenuItem.ToolTipText = "Detalles del programa";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(84, 109);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(56, 13);
            this.label2.TabIndex = 4;
            this.label2.Text = "BÁSCULA";
            // 
            // pictureBoxLector
            // 
            this.pictureBoxLector.BackColor = System.Drawing.Color.Red;
            this.pictureBoxLector.Location = new System.Drawing.Point(28, 138);
            this.pictureBoxLector.Name = "pictureBoxLector";
            this.pictureBoxLector.Size = new System.Drawing.Size(20, 20);
            this.pictureBoxLector.TabIndex = 3;
            this.pictureBoxLector.TabStop = false;
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(84, 181);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(101, 13);
            this.label4.TabIndex = 8;
            this.label4.Text = "LISTO PARA LEER";
            // 
            // pictureBoxListoLeer
            // 
            this.pictureBoxListoLeer.BackColor = System.Drawing.Color.Red;
            this.pictureBoxListoLeer.Location = new System.Drawing.Point(28, 174);
            this.pictureBoxListoLeer.Name = "pictureBoxListoLeer";
            this.pictureBoxListoLeer.Size = new System.Drawing.Size(20, 20);
            this.pictureBoxListoLeer.TabIndex = 7;
            this.pictureBoxListoLeer.TabStop = false;
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label5.Location = new System.Drawing.Point(12, 66);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(57, 13);
            this.label5.TabIndex = 9;
            this.label5.Text = "ESTADO";
            // 
            // label6
            // 
            this.label6.AutoSize = true;
            this.label6.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label6.Location = new System.Drawing.Point(84, 66);
            this.label6.Name = "label6";
            this.label6.Size = new System.Drawing.Size(91, 13);
            this.label6.TabIndex = 10;
            this.label6.Text = "DESCRIPCIÓN";
            // 
            // label7
            // 
            this.label7.AutoSize = true;
            this.label7.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label7.Location = new System.Drawing.Point(217, 66);
            this.label7.Name = "label7";
            this.label7.Size = new System.Drawing.Size(152, 13);
            this.label7.TabIndex = 11;
            this.label7.Text = "DETALLE DE LECTURAS";
            // 
            // dgvDatos
            // 
            this.dgvDatos.AllowUserToAddRows = false;
            this.dgvDatos.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
            | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.dgvDatos.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.dgvDatos.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.EPC,
            this.PESO});
            this.dgvDatos.Location = new System.Drawing.Point(220, 98);
            this.dgvDatos.Name = "dgvDatos";
            this.dgvDatos.Size = new System.Drawing.Size(345, 126);
            this.dgvDatos.TabIndex = 12;
            // 
            // EPC
            // 
            this.EPC.HeaderText = "EPC";
            this.EPC.Name = "EPC";
            this.EPC.Width = 200;
            // 
            // PESO
            // 
            this.PESO.HeaderText = "PESO";
            this.PESO.Name = "PESO";
            // 
            // statusStrip1
            // 
            this.statusStrip1.Location = new System.Drawing.Point(0, 295);
            this.statusStrip1.Name = "statusStrip1";
            this.statusStrip1.Size = new System.Drawing.Size(584, 22);
            this.statusStrip1.TabIndex = 13;
            this.statusStrip1.Text = "statusStrip1";
            // 
            // labelAvisos
            // 
            this.labelAvisos.AutoSize = true;
            this.labelAvisos.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.labelAvisos.Location = new System.Drawing.Point(217, 254);
            this.labelAvisos.Name = "labelAvisos";
            this.labelAvisos.Size = new System.Drawing.Size(44, 13);
            this.labelAvisos.TabIndex = 17;
            this.labelAvisos.Text = "Avisos";
            // 
            // buttonStart
            // 
            this.buttonStart.Location = new System.Drawing.Point(15, 232);
            this.buttonStart.Name = "buttonStart";
            this.buttonStart.Size = new System.Drawing.Size(179, 40);
            this.buttonStart.TabIndex = 20;
            this.buttonStart.Text = "Realizar lectura";
            this.buttonStart.UseVisualStyleBackColor = true;
            this.buttonStart.Click += new System.EventHandler(this.buttonStart_Click);
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(584, 317);
            this.Controls.Add(this.buttonStart);
            this.Controls.Add(this.labelAvisos);
            this.Controls.Add(this.statusStrip1);
            this.Controls.Add(this.dgvDatos);
            this.Controls.Add(this.label7);
            this.Controls.Add(this.label6);
            this.Controls.Add(this.label5);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.pictureBoxListoLeer);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.pictureBoxLector);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.pictureBoxBascula);
            this.Controls.Add(this.menuStrip1);
            this.MainMenuStrip = this.menuStrip1;
            this.Name = "Form1";
            this.Text = "BASCULA-COPEO";
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxBascula)).EndInit();
            this.menuStrip1.ResumeLayout(false);
            this.menuStrip1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLector)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxListoLeer)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.dgvDatos)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.PictureBox pictureBoxBascula;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.MenuStrip menuStrip1;
        private System.Windows.Forms.ToolStripMenuItem archivoToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem conectarToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem desconectarToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem salirToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem ayudaToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem toolStripMenuItem2;
        private System.Windows.Forms.ToolStripMenuItem acercaDeBASCULACOPEOToolStripMenuItem;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.PictureBox pictureBoxLector;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.PictureBox pictureBoxListoLeer;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.Label label7;
        private System.Windows.Forms.DataGridView dgvDatos;
        private System.Windows.Forms.StatusStrip statusStrip1;
        private System.Windows.Forms.Label labelAvisos;
        private System.Windows.Forms.Button buttonStart;
        private System.Windows.Forms.DataGridViewTextBoxColumn EPC;
        private System.Windows.Forms.DataGridViewTextBoxColumn PESO;
    }
}

