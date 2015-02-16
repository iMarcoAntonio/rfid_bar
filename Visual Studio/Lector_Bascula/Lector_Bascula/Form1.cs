using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using RFIDMEDevKit;
using System.Management;
using System.IO.Ports;
using System.Threading;
using System.IO;
using RestSharp;
using Newtonsoft.Json;

namespace Lector_Bascula
{
    public partial class Form1 : Form
    {
        public reader reader;
        private List<String> puertos;
        private String puerto;
        private String readerSelected;
        private SerialPort puertoBascula;
        private Thread thread;
        private Thread thread2;
        private bool hilo2;
        private bool hilo;

        public Form1()
        {
            InitializeComponent();
            this.puertos = new List<string>();
            this.puerto = "";
            this.readerSelected = "RFIDME";
            this.puertoBascula = new SerialPort();
            this.puertoBascula.BaudRate = 115200;
            this.puertoBascula.ReadTimeout = 2000;
            this.reader = new reader();
            this.hilo = false;
            this.hilo2 = false;
            Control.CheckForIllegalCrossThreadCalls = false;
        }

        private void conectarToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (this.DetectaLosPuertos() && this.ConectaConLector() && this.ConectaConElPuerto())
            {
                this.pictureBoxListoLeer.BackColor = Color.Green;
                this.labelAvisos.Text = "La conexión se realizó correctamente";
            }
        }

        private bool ConectaConLector()
        {
            try
            {
                this.reader.Activation("Demo");
                if(this.reader.Connect(this.readerSelected).Equals("Connected"))
                {
                    if (this.readerSelected.Equals("RU824"))
                    {
                        this.reader.setDwellTime(50);
                        this.reader.setNumInvCyc(50);
                        this.reader.setPower(240);
                    }
                    else
                    {
                        this.reader.setPower(18);
                    }
                    this.labelAvisos.Text = "Se conectó correctamente el lector " + this.readerSelected;
                    this.pictureBoxLector.BackColor = Color.Green;
                    return true;
                }
                else
                {
                    this.labelAvisos.Text = "No se logró establecer comunicación.";
                    return false;
                }
            }
            catch (Exception ex)
            {
                this.labelAvisos.Text = "No se logró establecer comunicación.\n" + ex.Message;
                return false;
            }
        }

        private bool ConectaConElPuerto()
        {
            try
            {
                if (this.puertos[0].Equals("COM3"))
                {
                    this.puertoBascula.PortName = this.puertos[0];
                    this.puertoBascula.Open();
                    this.labelAvisos.Text = "Se conectó correctamente con el puerto " + this.puertoBascula.PortName + ".";
                    this.puertoBascula.Write(new byte[] { 13, 10 }, 0, 2);
                    this.puertoBascula.ReadExisting().ToString();
                    this.puertoBascula.WriteLine("\r\n");
                    this.pictureBoxBascula.BackColor = Color.Green;
                    return true;
                }
                return false;
            }
            catch(Exception ex)
            {
                this.labelAvisos.Text = "No se logró establecer conexión con el puerto " + this.puertoBascula.PortName + ".\n" + ex.Message;
                return false;
            }
        }

        private bool DetectaLosPuertos()
        {

            try
            {
                this.puertos.Clear();
                foreach (PortInfo pi in PortInfo.GetPortsInfo())
                {
                    this.puertos.Add(pi.Name);
                }
                return true;
            }
            catch (Exception ex)
            {
                MessageBox.Show(this, "Error al detectar los puertos. " + ex.Message, "Error al detectar puertos", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return false;
            }
        }

        private void desconectarToolStripMenuItem_Click(object sender, EventArgs e)
        {
            try
            {
                if (this.puertoBascula.IsOpen)
                {
                    if (MessageBox.Show(this, "¿Seguro que quiere hacer la desconexión?", "Desconectar", MessageBoxButtons.YesNo, MessageBoxIcon.Question) == DialogResult.Yes)
                    {
                        this.reader.Disconnect();
                        this.puertoBascula.Close();
                        this.pictureBoxLector.BackColor = Color.Red;
                        this.pictureBoxBascula.BackColor = Color.Red;
                        this.pictureBoxListoLeer.BackColor = Color.Red;
                    }
                }
                else
                    MessageBox.Show("Los dispositivos no están conectados", "Dispositivos no conectados", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            catch (Exception ex)
            {
                MessageBox.Show(this, "Error al tratar de desconectar el lector y la bascula.\nError: " + ex.Message, "Error al desconectar", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void buttonStart_Click(object sender, EventArgs e)
        {
            try
            {
                if (this.puertoBascula.IsOpen)
                {
                    realizaLecturas();
                    this.POST();
                }
                else
                    MessageBox.Show(this, "Verifica que el lector y la báscula estén conectados correctamente", "Verifica la conexión", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (Exception ex)
            {
                MessageBox.Show(this, "Error al tratar de iniciar la lectura de tags.", "Error al iniciar lectura", MessageBoxButtons.OK, MessageBoxIcon.Exclamation);
            }
        }

        private void realizaLecturas()
        {
            try
            {
                String epc = leerEPC();
                String datos = obtenerPeso();
                String[] arrays = datos.Split('\r');
                if (arrays.Length > 1)
                {
                    String estado = arrays[0];
                    String peso = arrays[1];

                    if (epc != null && peso != null)
                    {
                        if (!epc.Equals(""))
                        {
                            if (!peso.Equals(""))
                            {
                                if (dgvDatos.Rows.Count >= 1)
                                {
                                    LinkedList<String> epcs = new LinkedList<string>();
                                    LinkedList<String> pesosLista = new LinkedList<string>();
                                    for (int i = 0; i < dgvDatos.Rows.Count; i++)
                                    {
                                        epcs.AddLast(Convert.ToString(dgvDatos.Rows[i].Cells["EPC"].Value));
                                        pesosLista.AddLast(Convert.ToString(dgvDatos.Rows[i].Cells["PESO"].Value));
                                    }
                                    if (!epc.Contains("No tags found"))
                                    {

                                        String po = peso.Trim();
                                        String[] ps = po.Split(new Char[] { ' ' });
                                        if (Convert.ToDouble(ps[0]) >= 0.200)
                                        {
                                            if (estado.Equals("LIBRE"))
                                            {
                                                dgvDatos.Rows.Insert(dgvDatos.Rows.Count, epc, peso);
                                                Clipboard.SetText(epc + "," + peso);



                                                epc = "";
                                                peso = "";
                                            }
                                            else
                                            {
                                                // MessageBox.Show(this,"Libere las barras para poder registrar los datos.", "Barras obstruidas",MessageBoxButtons.OK,MessageBoxIcon.Information);
                                                
                                                this.labelAvisos.Text = "Libere las barras \npara poder registrar \nlos datos.";
                                                
                                            }
                                        }
                                        else
                                        {
                                           
                                            this.labelAvisos.Text = "Peso menor a 200 gr.";
                                        }
                                    }
                                    else
                                    {
                                        this.labelAvisos.Text = "No se detecto ningún tag\nVuelva a intentar leer ";

                                    }
                                }
                                else
                                {
                                    if (!epc.Equals("No tags found"))
                                    {
                                        String po = peso.Trim();
                                        String[] ps = po.Split(new Char[] { ' ' });
                                        if (Convert.ToDouble(ps[0]) >= 0.200)
                                        {
                                            dgvDatos.Rows.Insert(0, epc, peso);
                                            Clipboard.SetText(epc + "," + peso);

                                            epc = "";
                                            peso = "";
                                        }
                                        else
                                        {
                                            this.labelAvisos.Text = "Peso menor a 200 gr.";
                                        }
                                    }
                                    else
                                    {
                                        this.labelAvisos.Text = "No se detectaron tags.";
                                    }
                                }
                            }
                            else
                            {
                                // MessageBox.Show(this,"Asegurese de que la báscula esté conectada y de que hay objetos que pesar.", "Revise la báscula",MessageBoxButtons.OK,MessageBoxIcon.Information);

                                this.labelAvisos.Text = "Asegurese de que la báscula esté conectada \ny de que haya objetos que pesar.";
                                
                                epc = "";
                                peso = "";
                            }
                        }
                        else
                        {
                            // MessageBox.Show(this,"Asegurese de que el Tag no esté dañado o de que haya Tag para escanear, no se detectó ningún valor.", "No se detectó ningún tag",MessageBoxButtons.OK,MessageBoxIcon.Information);
                            this.labelAvisos.Text = "No se detecto ningún tag\nVuelva a intentar leer ";

                            epc = "";
                            peso = "";
                        }
                    }
                    else
                    {
                        this.labelAvisos.Text = "No se detectó ningún dato.\nAsegurese de que hay peso sobre la báscula \ny que hay un tag.";
                        
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(this, "Error estableciendo datos de lectura y peso.\nError: " + ex.Message, "Error al llenar tabla", MessageBoxButtons.OK, MessageBoxIcon.Exclamation);
            }
        }
        private String leerEPC()
        {
            String data = "No tags found";
            String[] epcs = null;
            int intentos = 0;

            try
            {
                while (data.Equals("No tags found") && intentos != 20)
                {
                    data = reader.ReadEPC(false, ",");
                    epcs = data.Split(',');
                    intentos++;
                    data = epcs[0];
                }

                return data;
            }
            catch (Exception exc)
            {
                if (!exc.Message.Equals("Se interrumpió el estado de espera del subproceso."))
                {
                    MessageBox.Show(this, "Error leyendo EPCS: \n" + exc.Message, "Error al leer Tags", MessageBoxButtons.OK, MessageBoxIcon.Exclamation);
                }
                return null;
            }
        }

        private String obtenerPeso()
        {
            try
            {
                this.puertoBascula.WriteLine("\r\n");
                this.puertoBascula.DiscardOutBuffer();
                this.puertoBascula.DiscardInBuffer();
                String peso = this.puertoBascula.ReadTo("\r\n").ToString();
                return peso;

            }
            catch (Exception ex)
            {
                if (!ex.Message.Equals("Se interrumpió el estado de espera del subproceso."))
                {
                    MessageBox.Show(this, "Error obteniendo peso: \n" + ex.Message, "Error obteniendo peso", MessageBoxButtons.OK, MessageBoxIcon.Exclamation);
                }
                return null;
            }
        }

        public void POST()
        {
            try
            {
               /* Provider provider = new Provider();
                provider.name = "HQH";
                provider.created_at = DateTime.Now.ToString();
                provider.updated_at = DateTime.Now.ToString();

                File.AppendAllText("ordnesm.json", JsonConvert.SerializeObject(provider));

                RestClient client = new RestClient("http://localhost/portal_morin/public");
                RestRequest Request = new RestRequest("provider", Method.POST);
                Request.RequestFormat = DataFormat.Json;

                //Request.AddBody(JsonConvert.DeserializeObject<Provider>(File.ReadAllText("ordnesm.json")));

                IRestResponse response = client.Execute(Request);
                MessageBox.Show(response.StatusCode.ToString());*/
                RestClient cliente = new RestClient("http://rfid_feng");
                //cliente.Authenticator = new SimpleAuthenticator("username", "morin", "password", "morin");
                RestRequest request = new RestRequest("provider", Method.POST);

                //IRestResponse response1 = cliente.Execute(request);
                //MessageBox.Show(response1.Content + "\n\n");

                Provider provider = new Provider();
                provider.providername = "HQH";
                provider.created_at = DateTime.Now.ToString();
                provider.updated_at = DateTime.Now.ToString();

                String json = JsonConvert.SerializeObject(provider);
                request.AddJsonBody(provider);
                IRestResponse response = cliente.Execute(request);
                
                MessageBox.Show(response.Content+"\n\n"+json);
            }
            catch (Exception exc) { MessageBox.Show(exc.Message); }
        }
    }
}
