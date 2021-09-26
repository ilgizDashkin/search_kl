import React, {useContext} from 'react';
// https://material-ui.com/ru/components/autocomplete/#search-input компонент взят оттуда
import TextField from '@material-ui/core/TextField';
import Autocomplete from '@material-ui/lab/Autocomplete';
import { name_kl } from './name_kl.js'
import { Context } from '../context.js';

export default function FreeSolo() {
const [options, setOptions] = React.useState(name_kl);  
const {handleChange,setValue}=useContext(Context)//передаю функции изменения стейта с помощью контекста

  return (
    <div style={{ width: 300 }}>
      <Autocomplete
        id="free-solo-demo"
        freeSolo
        options={options}
        // выбор из списка
        onChange={(event, newValue) => {
          setOptions(newValue ? [newValue, ...options] : options);
          setValue(newValue);// получена из контекста
          // console.log(options)
        }}
        //ввод в поле поиска 
        onInputChange={(event, newInputValue) => {
          handleChange(event);//из контекста
          }}
        renderInput={(params) => (
          <TextField {...params} label="поиск кл" margin="normal" variant="outlined" />
        )}
      />
    </div>
  );
}
