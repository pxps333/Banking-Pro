<?php
$pageName = "Wire Transfer";
include_once("layouts/header.php");
$breadcrumbs = [['Home','./dashboard.php'],['Banking','#'],['Wire Transfer',null]];
include_once('layouts/breadcrumb.php');
require_once("userPinfunction.php");
?>

<div class="bp-grid-2" style="gap:24px;align-items:start;">

    <!-- Wire Transfer Form -->
    <div class="bp-card">
        <div class="bp-card-header">
            <h5 class="bp-card-title"><i class="ri-global-line" style="color:var(--bp-primary);margin-right:6px;"></i>International Wire Transfer</h5>
        </div>
        <div class="bp-card-body">
            <?php if($acct_stat === 'active'): ?>
            <?php if($page['transfer'] == '1'): ?>
            <?php if($row['transfer'] == '1'): ?>
            <form method="POST" enctype="multipart/form-data">
                <div style="display:flex;flex-direction:column;gap:18px;">

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Amount (<?= htmlspecialchars($currency) ?>)</label>
                            <div class="bp-input-group">
                                <span class="bp-input-prefix"><i class="ri-money-dollar-circle-line"></i></span>
                                <input type="number" class="bp-form-input" name="amount" placeholder="Enter amount" style="padding-left:38px;" required>
                            </div>
                            <div style="font-size:.73rem;color:var(--bp-text3);margin-top:3px;">
                                Available: <strong style="color:var(--bp-green);"><?= $currency . number_format($avail_balance, 2) ?></strong>
                            </div>
                        </div>
                        <div>
                            <label class="bp-form-label">Beneficiary Account Name</label>
                            <input type="text" class="bp-form-input" name="acct_name" placeholder="Full name on account" required>
                        </div>
                    </div>

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Bank Name</label>
                            <input type="text" class="bp-form-input" name="bank_name" placeholder="Receiving bank name" required>
                        </div>
                        <div>
                            <label class="bp-form-label">Beneficiary Account No</label>
                            <input type="number" class="bp-form-input" name="acct_number" placeholder="Account number" required>
                        </div>
                    </div>

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Select Country</label>
                            <select name="acct_country" class="bp-form-input" required>
                                <option value="">Select Country</option>
                                <option value="Afganistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="American Samoa">American Samoa</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Anguilla">Anguilla</option>
                                <option value="Antigua & Barbuda">Antigua &amp; Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Aruba">Aruba</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bermuda">Bermuda</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bonaire">Bonaire</option>
                                <option value="Bosnia & Herzegovina">Bosnia &amp; Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Canary Islands">Canary Islands</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Cayman Islands">Cayman Islands</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Channel Islands">Channel Islands</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Christmas Island">Christmas Island</option>
                                <option value="Cocos Island">Cocos Island</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Cook Islands">Cook Islands</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Cote DIvoire">Cote DIvoire</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Curaco">Curacao</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="East Timor">East Timor</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Falkland Islands">Falkland Islands</option>
                                <option value="Faroe Islands">Faroe Islands</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="French Guiana">French Guiana</option>
                                <option value="French Polynesia">French Polynesia</option>
                                <option value="French Southern Ter">French Southern Ter</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Gibraltar">Gibraltar</option>
                                <option value="Great Britain">Great Britain</option>
                                <option value="Greece">Greece</option>
                                <option value="Greenland">Greenland</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guadeloupe">Guadeloupe</option>
                                <option value="Guam">Guam</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Hawaii">Hawaii</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hong Kong">Hong Kong</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="India">India</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Isle of Man">Isle of Man</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
                                <option value="Korea North">Korea North</option>
                                <option value="Korea Sout">Korea South</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Macau">Macau</option>
                                <option value="Macedonia">Macedonia</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Martinique">Martinique</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mayotte">Mayotte</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Midway Islands">Midway Islands</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montserrat">Montserrat</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar">Myanmar</option>
                                <option value="Nambia">Nambia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherland Antilles">Netherland Antilles</option>
                                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                <option value="Nevis">Nevis</option>
                                <option value="New Caledonia">New Caledonia</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Niue">Niue</option>
                                <option value="Norfolk Island">Norfolk Island</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau Island">Palau Island</option>
                                <option value="Palestine">Palestine</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Phillipines">Philippines</option>
                                <option value="Pitcairn Island">Pitcairn Island</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Puerto Rico">Puerto Rico</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Republic of Montenegro">Republic of Montenegro</option>
                                <option value="Republic of Serbia">Republic of Serbia</option>
                                <option value="Reunion">Reunion</option>
                                <option value="Romania">Romania</option>
                                <option value="Russia">Russia</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="St Barthelemy">St Barthelemy</option>
                                <option value="St Eustatius">St Eustatius</option>
                                <option value="St Helena">St Helena</option>
                                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                <option value="St Lucia">St Lucia</option>
                                <option value="St Maarten">St Maarten</option>
                                <option value="St Pierre & Miquelon">St Pierre &amp; Miquelon</option>
                                <option value="St Vincent & Grenadines">St Vincent &amp; Grenadines</option>
                                <option value="Saipan">Saipan</option>
                                <option value="Samoa">Samoa</option>
                                <option value="Samoa American">Samoa American</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome & Principe">Sao Tome &amp; Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Tahiti">Tahiti</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Togo">Togo</option>
                                <option value="Tokelau">Tokelau</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad & Tobago">Trinidad &amp; Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Turks & Caicos Is">Turks &amp; Caicos Is</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Erimates">United Arab Emirates</option>
                                <option value="United States of America">United States of America</option>
                                <option value="Uraguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Vatican City State">Vatican City State</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                <option value="Wake Island">Wake Island</option>
                                <option value="Wallis & Futana Is">Wallis &amp; Futana Is</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zaire">Zaire</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                        </div>
                        <div>
                            <label class="bp-form-label">Swift Code</label>
                            <input type="text" class="bp-form-input" name="acct_swift" placeholder="e.g. BNPAFRPP" required>
                        </div>
                    </div>

                    <div class="bp-grid-2" style="gap:14px;">
                        <div>
                            <label class="bp-form-label">Routing Number</label>
                            <input type="number" class="bp-form-input" name="acct_routing" placeholder="Routing / Sort code" required>
                        </div>
                        <div>
                            <label class="bp-form-label">Account Type</label>
                            <select name="acct_type" class="bp-form-input" required>
                                <option value="">Select Account Type</option>
                                <option value="Savings">Savings Account</option>
                                <option value="Current">Current Account</option>
                                <option value="Checking">Checking Account</option>
                                <option value="Fixed Deposit">Fixed Deposit</option>
                                <option value="Non Resident">Non Resident Account</option>
                                <option value="Online Banking">Online Banking</option>
                                <option value="Domicilary Account">Domicilary Account</option>
                                <option value="Joint Account">Joint Account</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="bp-form-label">Narration / Purpose</label>
                        <textarea class="bp-form-input" name="acct_remarks" rows="3" placeholder="Fund description" style="resize:none;"></textarea>
                    </div>

                    <button type="submit" name="wire_transfer" class="bp-btn-primary" style="width:100%;justify-content:center;padding:12px;">
                        <i class="ri-send-plane-line"></i> Send Wire Transfer
                    </button>
                </div>
            </form>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Wire Transfer Disabled</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">You do not have permission to make wire transfers. Contact support.</div>
                    <a href="mailto:<?= htmlspecialchars($page['url_email']) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Us
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Service Unavailable</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">Transfer service is currently unavailable. Please contact support.</div>
                    <a href="mailto:<?= htmlspecialchars($page['url_email']) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Us
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:20px;display:flex;align-items:flex-start;gap:12px;">
                <i class="ri-error-warning-line" style="color:var(--bp-red);font-size:1.3rem;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--bp-text);margin-bottom:4px;">Account on Hold</div>
                    <div style="font-size:.8rem;color:var(--bp-text2);">Your account is on hold. Contact support to restore access.</div>
                    <a href="mailto:<?= htmlspecialchars($page['url_email']) ?>" class="bp-btn-outline" style="margin-top:12px;font-size:.8rem;padding:7px 14px;">
                        <i class="ri-mail-line"></i> Contact Us
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Wire Transfer Info -->
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="bp-card">
            <div class="bp-card-header">
                <h5 class="bp-card-title"><i class="ri-information-line" style="color:var(--bp-cyan);margin-right:6px;"></i>Wire Transfer Info</h5>
            </div>
            <div class="bp-card-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <?php
                    $tips = [
                        ['icon'=>'ri-time-line','color'=>'var(--bp-orange)','text'=>'International wire transfers take 2-5 business days'],
                        ['icon'=>'ri-shield-check-line','color'=>'var(--bp-green)','text'=>'Verify all beneficiary details carefully before submitting'],
                        ['icon'=>'ri-earth-line','color'=>'var(--bp-primary)','text'=>'SWIFT/BIC code is required for international transfers'],
                        ['icon'=>'ri-alarm-warning-line','color'=>'var(--bp-red)','text'=>'Wire transfers cannot be reversed once submitted'],
                    ];
                    foreach($tips as $t): ?>
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <i class="<?= $t['icon'] ?>" style="color:<?= $t['color'] ?>;font-size:1rem;margin-top:2px;flex-shrink:0;"></i>
                        <span style="font-size:.8rem;color:var(--bp-text2);"><?= $t['text'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:18px;background:rgba(67,97,238,0.06);border:1px solid rgba(67,97,238,0.15);border-radius:10px;padding:14px;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--bp-primary);margin-bottom:8px;">Account Balance</div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--bp-text);"><?= $currency . number_format($acct_balance, 2) ?></div>
                    <div style="font-size:.78rem;color:var(--bp-green);margin-top:2px;"><i class="ri-checkbox-circle-line"></i> Available: <?= $currency . number_format($avail_balance, 2) ?></div>
                </div>

                <div style="margin-top:14px;">
                    <a href="./domestic-transfer.php" class="bp-btn-outline" style="width:100%;justify-content:center;padding:10px;">
                        <i class="ri-exchange-dollar-line"></i> Switch to Domestic Transfer
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include_once("layouts/footer.php"); ?>
