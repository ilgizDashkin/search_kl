import React, {useState, useContext} from 'react';
import TextField from '@material-ui/core/TextField';
import Autocomplete from '@material-ui/lab/Autocomplete';
import { name_kl } from './name_kl.js'
import { Context } from '../context.js';

export default function FreeSolo() {
  // const [value, setValue] = useState(null);
  // const [inputValue, setInputValue] = useState('');
  // const [options, setOptions] = useState([]);
const handleChange=useContext(Context)

  return (
    <div style={{ width: 300 }}>
      <Autocomplete
        id="free-solo-demo"
        freeSolo
        options={name_kl}
        // value={value}
        // onChange={(event, newValue) => {
        //   setOptions(newValue ? [newValue, ...options] : options);
        //   setValue(newValue);
        // }}
        // onInputChange={(event, newInputValue) => {
        //   setInputValue(newInputValue);
        // }}
        // onChange={(event, newValue) => {
        //   setOptions(newValue ? [newValue, ...options] : options);
        //   setValue(newValue);
        // }}
        onInputChange={(event, newInputValue) => {
          handleChange(event);
          }}
        renderInput={(params) => (
          <TextField {...params} label="freeSolo" margin="normal" variant="outlined" />
        )}
      />
    </div>
  );
}
