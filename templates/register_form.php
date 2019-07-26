<?php
$first_name     = '';
$last_name      = '';
$email          = '';
$country_id     = '';
$login_name     = '';
$date_of_birth  = '';
$nic_passport   = '';
$street_address = '';
$telephone      = '';

if(isset($_POST['first_name']))
{
    $first_name     = $_POST['first_name'];
}

if(isset($_POST['last_name']))
{
    $last_name     = $_POST['last_name'];
}

if(isset($_POST['email']))
{
    $email     = $_POST['email'];
}

if(isset($_POST['country_id']))
{
    $country_id     = $_POST['country_id'];
}

if(isset($_POST['date_of_birth']))
{
    $date_of_birth     = $_POST['date_of_birth'];
}

if(isset($_POST['nic_passport']))
{
    $nic_passport     = $_POST['nic_passport'];
}


if(isset($_POST['street_address']))
{
    $street_address     = $_POST['street_address'];
}
if(isset($_POST['telephone']))
{
    $telephone     = $_POST['telephone'];
}

?>
<div id="register-form" class="widecolumn">
<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
    <?php foreach ( $attributes['errors'] as $error ) : ?>
        <p>
            <?php echo $error; ?>
        </p>
    <?php endforeach; ?>
<?php endif; ?>    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Register', 'personalize-login' ); ?></h3>
    <?php endif; ?>
 
    <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
    <p class="form-row">
            <label for="first_name"><?php _e( 'Name', 'personalize-login' ); ?></label>
            <input type="text" name="first_name" value="<?php echo $first; ?>" id="first-name">
        </p>
 
        <p class="form-row">
            <label for="last_name"><?php _e( 'Father\'s name', 'personalize-login' ); ?></label>
            <input type="text" name="last_name" value="<?php echo $last; ?>" id="last-name">
        </p>
        <p class="form-row">
            <label for="email"><?php _e( 'Email', 'personalize-login' ); ?> <strong>*</strong></label>
            <input type="email" name="email" value="<?php echo $email; ?>" id="email">
        </p>
        <p class="form-row">
            <label for="country_id"><?php _e( 'Country', 'personalize-login' ); ?> <strong>*</strong></label>
            <select name="country_id" value="<?php echo $country_id; ?>" id="country_id"><option value="none">Please Select</option><option value="1">Afghanistan</option><option value="3">Albania</option><option value="4">Algeria</option><option value="5">American Samoa</option><option value="6">Andorra</option><option value="7">Angola</option><option value="8">Anguilla</option><option value="9">Antarctica</option><option value="10">Antigua And Barbuda</option><option value="11">Argentina</option><option value="12">Armenia</option><option value="13">Aruba</option><option value="14">Australia</option><option value="15">Austria</option><option value="16">Azerbaijan</option><option value="17">Bahamas</option><option value="18">Bahrain</option><option value="19">Bangladesh</option><option value="20">Barbados</option><option value="21">Belarus</option><option value="22">Belgium</option><option value="23">Belize</option><option value="24">Benin</option><option value="25">Bermuda</option><option value="26">Bhutan</option><option value="27">Bolivia</option><option value="28">Bosnia And Herzegovina</option><option value="29">Botswana</option><option value="30">Bouvet Island</option><option value="31">Brazil</option><option value="32">British Indian Ocean Territory</option><option value="33">Brunei Darussalam</option><option value="34">Bulgaria</option><option value="35">Burkina Faso</option><option value="36">Burundi</option><option value="37">Cambodia</option><option value="38">Cameroon</option><option value="39">Canada</option><option value="40">Cape Verde</option><option value="41">Cayman Islands</option><option value="42">Central African Republic</option><option value="43">Chad</option><option value="44">Chile</option><option value="45">China</option><option value="46">Christmas Island</option><option value="47">Cocos (Keeling) Islands</option><option value="48">Colombia</option><option value="49">Comoros</option><option value="50">Congo</option><option value="51">Congo, The Democratic Republic Of The</option><option value="52">Cook Islands</option><option value="53">Costa Rica</option><option value="54">Cote D'Ivoire</option><option value="55">Croatia</option><option value="56">Cuba</option><option value="57">Cyprus</option><option value="58">Czech Republic</option><option value="59">Denmark</option><option value="60">Djibouti</option><option value="61">Dominica</option><option value="62">Dominican Republic</option><option value="63">Ecuador</option><option value="64">Egypt</option><option value="65">El Salvador</option><option value="66">Equatorial Guinea</option><option value="67">Eritrea</option><option value="68">Estonia</option><option value="69">Ethiopia</option><option value="70">Falkland Islands (Malvinas)</option><option value="71">Faroe Islands</option><option value="72">Fiji</option><option value="73">Finland</option><option value="74">France</option><option value="75">French Guiana</option><option value="76">French Polynesia</option><option value="77">French Southern Territories</option><option value="78">Gabon</option><option value="79">Gambia</option><option value="80">Georgia</option><option value="81">Germany</option><option value="82">Ghana</option><option value="83">Gibraltar</option><option value="84">Greece</option><option value="85">Greenland</option><option value="86">Grenada</option><option value="87">Guadeloupe</option><option value="88">Guam</option><option value="89">Guatemala</option><option value="90">Guernsey</option><option value="91">Guinea</option><option value="92">Guinea-Bissau</option><option value="93">Guyana</option><option value="94">Haiti</option><option value="95">Heard Island And Mcdonald Islands</option><option value="96">Holy See (Vatican City State)</option><option value="97">Honduras</option><option value="98">Hong Kong</option><option value="99">Hungary</option><option value="100">Iceland</option><option value="101">India</option><option value="102">Indonesia</option><option value="103">Iran, Islamic Republic Of</option><option value="104">Iraq</option><option value="105">Ireland</option><option value="106">Isle Of Man</option><option value="107">Israel</option><option value="108">Italy</option><option value="109">Jamaica</option><option value="110">Japan</option><option value="111">Jersey</option><option value="112">Jordan</option><option value="113">Kazakhstan</option><option value="114">Kenya</option><option value="115">Kiribati</option><option value="116">Korea, Democratic People'S Republic Of</option><option value="117">Korea, Republic Of</option><option value="118">Kuwait</option><option value="119">Kyrgyzstan</option><option value="120">Lao People'S Democratic Republic</option><option value="121">Latvia</option><option value="122">Lebanon</option><option value="123">Lesotho</option><option value="124">Liberia</option><option value="125">Libyan Arab Jamahiriya</option><option value="126">Liechtenstein</option><option value="127">Lithuania</option><option value="128">Luxembourg</option><option value="129">Macao</option><option value="130">Macedonia, The Former Yugoslav Republic Of</option><option value="131">Madagascar</option><option value="132">Malawi</option><option value="133">Malaysia</option><option value="134">Maldives</option><option value="135">Mali</option><option value="136">Malta</option><option value="137">Marshall Islands</option><option value="138">Martinique</option><option value="139">Mauritania</option><option value="140">Mauritius</option><option value="141">Mayotte</option><option value="142">Mexico</option><option value="143">Micronesia, Federated States Of</option><option value="144">Moldova, Republic Of</option><option value="145">Monaco</option><option value="146">Mongolia</option><option value="147">Montserrat</option><option value="148">Morocco</option><option value="149">Mozambique</option><option value="150">Myanmar</option><option value="151">Namibia</option><option value="152">Nauru</option><option value="153">Nepal</option><option value="154">Netherlands</option><option value="155">Netherlands Antilles</option><option value="156">New Caledonia</option><option value="157">New Zealand</option><option value="158">Nicaragua</option><option value="159">Niger</option><option value="160">Nigeria</option><option value="161">Niue</option><option value="162">Norfolk Island</option><option value="163">Northern Mariana Islands</option><option value="164">Norway</option><option value="165">Oman</option><option value="166">Pakistan</option><option value="167">Palau</option><option value="168">Palestinian Territory, Occupied</option><option value="169">Panama</option><option value="170">Papua New Guinea</option><option value="171">Paraguay</option><option value="172">Peru</option><option value="173">Philippines</option><option value="174">Pitcairn</option><option value="175">Poland</option><option value="176">Portugal</option><option value="177">Puerto Rico</option><option value="178">Qatar</option><option value="179">Reunion</option><option value="180">Romania</option><option value="181">Russian Federation</option><option value="182">Rwanda</option><option value="183">Saint Helena</option><option value="184">Saint Kitts And Nevis</option><option value="185">Saint Lucia</option><option value="186">Saint Pierre And Miquelon</option><option value="187">Saint Vincent And The Grenadines</option><option value="188">Samoa</option><option value="189">San Marino</option><option value="190">Sao Tome And Principe</option><option value="191">Saudi Arabia</option><option value="192">Senegal</option><option value="193">Serbia And Montenegro</option><option value="194">Seychelles</option><option value="195">Sierra Leone</option><option value="196">Singapore</option><option value="197">Slovakia</option><option value="198">Slovenia</option><option value="199">Solomon Islands</option><option value="200">Somalia</option><option value="201">South Africa</option><option value="202">South Georgia And The South Sandwich Islands</option><option value="203">Spain</option><option value="204">Sri Lanka</option><option value="205">Sudan</option><option value="206">Suriname</option><option value="207">Svalbard And Jan Mayen</option><option value="208">Swaziland</option><option value="209">Sweden</option><option value="210">Switzerland</option><option value="211">Syrian Arab Republic</option><option value="212">Taiwan, Province Of China</option><option value="213">Tajikistan</option><option value="214">Tanzania, United Republic Of</option><option value="215">Thailand</option><option value="216">Timor-Leste</option><option value="217">Togo</option><option value="218">Tokelau</option><option value="219">Tonga</option><option value="220">Trinidad And Tobago</option><option value="221">Tunisia</option><option value="222">Turkey</option><option value="223">Turkmenistan</option><option value="224">Turks And Caicos Islands</option><option value="225">Tuvalu</option><option value="226">Uganda</option><option value="227">Ukraine</option><option value="228">United Arab Emirates</option><option value="229">United Kingdom</option><option value="230">United States</option><option value="231">United States Minor Outlying Islands</option><option value="232">Uruguay</option><option value="233">Uzbekistan</option><option value="234">Vanuatu</option><option value="235">Venezuela</option><option value="236">Viet Nam</option><option value="237">Virgin Islands, British</option><option value="238">Virgin Islands, U.S.</option><option value="239">Wallis And Futuna</option><option value="240">Western Sahara</option><option value="241">Yemen</option><option value="242">Zambia</option><option value="243">Zimbabwe</option><option value="2">ÅLand Islands</option></select>
        </p>
        <p class="form-row">
            <label for="login_name"><?php _e( 'Login Name', 'personalize-login' ); ?> <strong>*</strong></label>
            <input type="text" name="login_name" value="<?php echo $login_name; ?>" id="login_name">
        </p>

        <p class="form-row">
            <label for="date_of_birth">Date of birth</label>
            <input type="date" name="date_of_birth" value="<?php echo $date_of_birth; ?>" id="date_of_birth">
					   
					  <!-- <select id="daydropdown" name="daydropdown" style="width:70px">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
					  </select>
					  <select id="monthdropdown" name="monthdropdown" style="width:90px">
								<option value="1">Jan</option>
								<option value="2">Feb</option>
								<option value="3">Mar</option>
								<option value="4">Apr</option>
								<option value="5">May</option>
								<option value="6">Jun</option>
								<option value="7">Jul</option>
								<option value="8">Aug</option>
								<option value="9">Sep</option>
								<option value="10">Oct</option>
								<option value="11">Nov</option>
								<option value="12">Dec</option>
					  </select>
					  <select id="yeardropdown" name="yeardropdown" style="width:80px;">

								<option value="2005">2005</option>
								<option value="2004">2004</option>
								<option value="2003">2003</option>
								<option value="2002">2002</option>
								<option value="2001">2001</option>
								<option value="2000">2000</option>
								<option value="1999">1999</option>
								<option value="1998">1998</option>
								<option value="1997">1997</option>
								<option value="1996">1996</option>
								<option value="1995">1995</option>
								<option value="1994">1994</option>
								<option value="1993">1993</option>
								<option value="1992">1992</option>
								<option value="1991">1991</option>
								<option value="1990">1990</option>
								<option value="1989">1989</option>
								<option value="1988">1988</option>
								<option value="1987">1987</option>
								<option value="1986">1986</option>
								<option value="1985">1985</option>
								<option value="1984">1984</option>
								<option value="1983">1983</option>
								<option value="1982">1982</option>
								<option value="1981">1981</option>
								<option value="1980">1980</option>
								<option value="1979">1979</option>
								<option value="1978">1978</option>
								<option value="1977">1977</option>
								<option value="1976">1976</option>
								<option value="1975">1975</option>
								<option value="1974">1974</option>
								<option value="1973">1973</option>
								<option value="1972">1972</option>
								<option value="1971">1971</option>
								<option value="1970">1970</option>
								<option value="1969">1969</option>
								<option value="1968">1968</option>
								<option value="1967">1967</option>
								<option value="1966">1966</option>
								<option value="1965">1965</option>
								<option value="1964">1964</option>
								<option value="1963">1963</option>
								<option value="1962">1962</option>
								<option value="1961">1961</option>
								<option value="1960">1960</option>
								<option value="1959">1959</option>
								<option value="1958">1958</option>
								<option value="1957">1957</option>
								<option value="1956">1956</option>
								<option value="1955">1955</option>
								<option value="1954">1954</option>
								<option value="1953">1953</option>
								<option value="1952">1952</option>
								<option value="1951">1951</option>
				</select> -->
				
        </p>
       
        <p class="form-row">
            <label for="nic_passport">NIC/Passport</label>
            <input type="text" name="nic_passport" value="<?php echo $nic_passport;?>" id="nic_passport">
        </p>
        <p class="form-row">
            <label for="street_address">Street Address</label>
            <textarea name="street_address" value="<?php echo $street_address;?>" id="street_address"></textarea>
        </p>
 
        <p class="form-row">
            <label for="telephone">Telephone</label>
            <input type="tel" name="telephone" value="<?php echo $telephone;?>" id="telephone">
        </p>
 
        <p class="form-row">
            <?php _e( 'Note: Your password will be generated automatically and sent to your email address.', 'personalize-login' ); ?>
        </p>
    
        <p class="signup-submit" >
        <?php if ( $attributes['recaptcha_site_key'] ) : ?>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $attributes['recaptcha_site_key']; ?>"></div>
            </div>
        <?php endif; ?>
            <input type="submit" name="submit" class="register-button"
                   value="<?php _e( 'Register', 'personalize-login' ); ?>"/>
        </p>
    </form>
</div>